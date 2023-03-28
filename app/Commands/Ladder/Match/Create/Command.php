<?php

namespace App\Commands\Ladder\Match\Create;

use App\Commands\AbstractCommand;
use App\Commands\User\Bank\Transaction\Charge\Command as BankChargeCommand;
use App\Commands\User\Eligibility\Command as EligibilityCommand;
use App\DataSource\Ladder\Mapper as LadderMapper;
use App\DataSource\Ladder\Gametype\Mapper as GametypeMapper;
use App\DataSource\Ladder\Match\Mapper as MatchMapper;
use App\DataSource\Ladder\Match\Report\Mapper as MatchReportMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\DataSource\Ladder\Team\Member\Mapper as TeamMemberMapper;
use App\Services\Match\MapsetGenerator;
use Closure;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(
        BankChargeCommand $charge,
        EligibilityCommand $eligibility,
        Filter $filter,
        GametypeMapper $gametype,
        LadderMapper $ladder,
        MapsetGenerator $mapset,
        MatchMapper $match,
        MatchReportMapper $report,
        TeamMapper $team,
        TeamMemberMapper $member
    ) {
        $this->command = compact('charge', 'eligibility');
        $this->filter = $filter;
        $this->mapper = compact('gametype', 'ladder', 'match', 'member', 'report', 'team');
        $this->service = compact('mapset');
    }


    protected function run(
        int $bestOf,
        int $gametype,
        int $ladder,
        array $modifiers,
        array $roster,
        int $team,
        int $teamsPerMatch,
        int $user,
        float $wager
    ) : bool {
        $gametype = $this->mapper['gametype']->findById($gametype);
        $ladder = $this->mapper['ladder']->findById($ladder);
        $members = $this->mapper['member']->findByTeam($team);
        $team = $this->mapper['team']->findById($team);

        if ($gametype->isEmpty() || $ladder->isEmpty() || $members->isEmpty() || $team->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $member = $members->get('user', $user, $this->mapper['member']->create());
            $modifiers = array_intersect($gametype->getModifiers(), $modifiers);
            $roster = array_intersect($members->column('user'), $roster);
            $playersPerTeam = count($roster);

            // Ladder Is Open
            if ($ladder->isClosed()) {
                $this->filter->writeLadderClosedMessage();
            }
            elseif (!$ladder->started()) {
                $this->filter->writeLadderStartsMessage('Cannot create match, ', $ladder->getStartsAt());
            }
            // Validate Best Of
            elseif (!in_array($bestOf, $gametype->getBestOf())) {
                $this->filter->writeInvalidBestOfMessage();
            }
            // Validate Teams Per Match
            elseif (!in_array($teamsPerMatch, $gametype->getTeamsPerMatch())) {
                $this->filter->writeInvalidTeamsPerMatchMessage();
            }
            // Validate Player Per Team Size
            elseif (!in_array($playersPerTeam, $gametype->getPlayersPerTeam())) {
                $this->filter->writeInvalidPlayersPerTeamMessage();
            }
            // Member Auth Check
            elseif (!$member->managesMatches()) {
                $this->filter->writeUnauthorizedMessage();
            }
            // Check Stop Loss
            elseif ($ladder->getStopLoss() > 0 && ($team->getLosses() + count($this->mapper['report']->findDisputedMatchIdsByTeam($team->getId()))) >= $ladder->getStopLoss()) {
                $this->filter->writeStopLossMessage();
            }
            // Check Locked team
            elseif ($ladder->isTeamLockRequired() && !$team->isLocked()) {
                $this->filter->writeLockTeamMessage();
            }
            else {
                // Verify Participating Members Eligibility
                $this->delegate($this->command['eligibility'], [
                    'amount' => $wager,
                    'game' => $ladder->getGame(),
                    'membership' => $ladder->isMembershipRequired(),
                    'users' => $roster,
                    'wagers' => ($wager > 0)
                ]);

                // Make Sure All Participating Members Reported All Of Their Previous Ladder Matches
                foreach ($roster as $p) {
                    $unreported = $this->mapper['report']->findUserTeamsWithUnreportedMatches(
                        $p, ...$this->mapper['member']->findTeamsByUser($p)->column('team')
                    );

                    if (count($unreported)) {
                        $this->filter->writeReportActiveMatchesMessage();
                        break;
                    }
                }
            }
        }

        if (!$this->filter->hasErrors()) {
            $match = array_merge(compact('bestOf', 'modifiers', 'playersPerTeam', 'teamsPerMatch', 'user', 'wager'), [
                'gametype' => $gametype->getId(),
                'ladder' => $ladder->getId(),
                'mapset' => $this->service['mapset']->generate($bestOf, $gametype->getMapsets()),
                'team' => $team->getId()
            ]);
            $memo = "'{$ladder->getName()}' Wager Match With Team '{$team->getName()}'";
            $report = [
                'roster' => $roster,
                'team' => $team->getId(),
                'user' => $user
            ];

            $this->mapper['match']->transaction(function (Closure $rollback) use ($match, $memo, $report) {
                $this->mapper['match']->insert(
                    $match = $this->mapper['match']->create($match)
                );

                $this->mapper['report']->transaction(function (Closure $rollback) use ($match, $memo, $report) {
                    $this->mapper['report']->insert(
                        $report = $this->mapper['report']->create(array_merge($report, ['match' => $match->getId()]))
                    );

                    // Charge Wager Fees
                    if ($match->isWager()) {
                        $this->delegate($this->command['charge'], [
                            'amount' => $match->getWager(),
                            'ladder' => $match->getLadder(),
                            'ladderMatch' => $match->getId(),
                            'memo' => $memo,
                            'team' => $report->getTeam(),
                            'users' => $report->getRoster()
                        ]);
                    }

                    if ($this->filter->hasErrors()) {
                        $rollback();
                    }
                });

                if ($this->filter->hasErrors()) {
                    $rollback();
                }
            });
        }

        return $this->booleanResult();
    }
}
