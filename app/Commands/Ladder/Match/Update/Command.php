<?php

namespace App\Commands\Ladder\Match\Update;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\Mapper as LadderMapper;
use App\DataSource\Ladder\Match\Mapper as MatchMapper;
use App\DataSource\Ladder\Match\Report\Mapper as MatchReportMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\DataSource\User\Rank\Mapper as UserRankMapper;
use App\DataSource\User\Bank\Transaction\Mapper as UserBankTransactionMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(
        Filter $filter,
        LadderMapper $ladder,
        MatchMapper $match,
        MatchReportMapper $report,
        TeamMapper $team,
        UserBankTransactionMapper $transaction,
        UserRankMapper $rank
    ) {
        $this->filter = $filter;
        $this->mapper = compact('ladder', 'match', 'rank', 'report', 'team', 'transaction');
    }


    protected function run(int $id) :  void
    {
        $match = $this->mapper['match']->findById($id);

        if ($match->isEmpty() || $match->isComplete() || (!$match->isActive() && !$match->isDisputed())) {
            return;
        }

        $ladder = $this->mapper['ladder']->findById($match->getLadder());
        $reports = $this->mapper['report']->findByMatch($match->getId());

        // Missing Report From Team; Bail
        if (!$reports->allTeamsReported($match->getTeamsPerMatch())) {
            return;
        }
        // Multiple Teams Claim 'x' Placement, Mark As Dispute
        // Or
        // Placement Does Not Exist
        elseif ($reports->invalidReportFound($match->getTeamsPerMatch())) {
            if (!$match->isDisputed()) {
                $match->dispute();
                $this->mapper['match']->update($match);
            }
            return;
        }

        // Match Was Reported Correctly Mark As Complete
        $match->complete($reports->getWinningTeamId());
        $this->mapper['match']->update($match);

        // Update Match Counter
        $this->mapper['ladder']->pushTotalMatchesPlayedQueue($ladder->getId());

        $rosters = $reports->sortRostersByPlacement();
        $teams = $reports->sortTeamsByPlacement();

        // Schedule Necessary Jobs
        $this->mapper['rank']->scheduleUpdateJob([
            'game' => $ladder->getGame(),
            'rosters' => $rosters
        ]);
        $this->mapper['team']->scheduleUpdateGlicko2Job(['ids' => $teams]);

        if ($match->isWager()) {
            $this->mapper['transaction']->scheduleLadderMatchPayoutJob([
                'ladderMatch' => $match->getId(),
                'users' => $rosters[0],
                'team' => $teams[0]
            ]);
        }
    }
}
