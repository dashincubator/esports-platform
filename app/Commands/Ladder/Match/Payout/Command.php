<?php

namespace App\Commands\Ladder\Match\Payout;

use App\Commands\AbstractCommand;
use App\Commands\User\Bank\Transaction\Earned\Command as BankEarnedCommand;
use App\DataSource\Ladder\Mapper as LadderMapper;
use App\DataSource\Ladder\Match\Mapper as MatchMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\DataSource\User\Bank\Transaction\Mapper as TransactionMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(
        BankEarnedCommand $earned,
        Filter $filter,
        LadderMapper $ladder,
        MatchMapper $match,
        TeamMapper $team,
        TransactionMapper $transaction
    ) {
        $this->command = compact('earned');
        $this->filter = $filter;
        $this->mapper = compact('ladder', 'match', 'team', 'transaction');
    }


    /**
     *  @param int $ladderMatch Match Requiring Payout
     *  @param int $team Winning Team ID ( Used For Memo )
     *  @param array $user Winning Team User Ids ( Used For Paying Users )
     *  @return bool True On Successful Payout, Otherwise False
     */
    protected function run(int $ladderMatch, int $team, array $users) : bool
    {
        $match = $this->mapper['match']->findById($id);

        if (!$match->isComplete() || !$match->isWager() || $match->isWagerComplete()) {
            $this->filter->writeUnknownErrorMessage();
        }
        // Look For Paid Transactions Just In Case
        elseif (!$this->mapper['transaction']->findPayoutsByLadderMatchAndTeam($match->getId(), $team)->isEmpty()) {
            $this->filter->writeMatchAlreadyPaidMessage();
        }

        if (!$this->filter->hasErrors()) {
            $ladder = $this->mapper['ladder']->findById($match->getLadder());
            $team = $this->mapper['team']->findById($team);

            $this->mapper['match']->transaction(function() use ($ladder, $match, $team, $users) {
                $memo = "Won '{$ladder->getName()}' Wager Match With Team '{$team->getName()}'";

                $this->delegate($this->command['earned'], [
                    'amount' = $match->getWager(),
                    'game' => $ladder->getGame(),
                    'ladder' => $match->getLadder(),
                    'ladderMatch' => $match->getId(),
                    'memo' => $memo,
                    'team' => $team->getId(),
                    'users' => $users
                ]);

                if (!$this->filter->hasErrors()) {
                    $match->wagerComplete();
                    $this->mapper['match']->update($match);
                    $this->mapper['ladder']->pushTotalWageredQueue($match->getLadder(), ($match->getWager() * count($users)));
                }
            });
        }

        return $this->booleanResult();
    }
}
