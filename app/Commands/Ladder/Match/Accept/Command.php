<?php

namespace App\Commands\Ladder\Match\Accept;

use App\Commands\AbstractCommand;
use App\Commands\User\Bank\Transaction\Charge\Command as BankChargeCommand;
use App\Commands\User\Eligibility\Command as EligibilityCommand;
use App\DataSource\Ladder\Mapper as LadderMapper;
use App\DataSource\Ladder\Match\{Entity as MatchEntity, Mapper as MatchMapper};
use App\DataSource\Ladder\Match\Report\Mapper as MatchReportMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\DataSource\Ladder\Team\Member\Mapper as TeamMemberMapper;
use App\Services\Match\SortHost as HostService;
use Closure;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(
        BankChargeCommand $charge,
        EligibilityCommand $eligibility,
        Filter $filter,
        HostService $host,
        LadderMapper $ladder,
        MatchMapper $match,
        MatchReportMapper $report,
        TeamMapper $team,
        TeamMemberMapper $member
    ) {
        $this->command = compact('charge', 'eligibility');
        $this->filter = $filter;
        $this->mapper = compact('ladder', 'match', 'member', 'report', 'team');
        $this->service = compact('host');
    }


    protected function run(int $id, array $roster, int $team, int $user) : MatchEntity
    {
        $match = $this->mapper['match']->findById($id);

        if ($match->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $ladder = $this->mapper['ladder']->findById($match->getLadder());
            $members = $this->mapper['member']->findByTeam($team);
            $team = $this->mapper['team']->findById($team);

            if ($ladder->isEmpty() || $members->isEmpty() || $team->isEmpty()) {
                $this->filter->writeUnknownErrorMessage();
            }
            else {
                $member = $members->get('user', $user, $this->mapper['member']->create());
                $roster = array_intersect($members->column('user'), $roster);

                // Ladder Is Open
                if ($ladder->isClosed()) {
                    $this->filter->writeLadderClosedMessage();
                }
                elseif (!$ladder->started()) {
                    $this->filter->writeLadderStartsMessage('Cannot accept match, ', $ladder->getStartsAt());
                }
                // Verify Matchfinder Match
                elseif (!$match->inMatchFinder()) {
                    $this->filter->writeActiveMatchMessage();
                }
                // Validate Player Per Team Size For Match
                elseif ($match->getPlayersPerTeam() !== count($roster)) {
                    $this->filter->writeInvalidPlayersPerTeamMessage();
                }
                // Member Auth Check
                elseif (!$member->managesMatches()) {
                    $this->filter->writeUnauthorizedMessage();
                }
                // Can't Accept Own Team Match
                elseif ($match->getTeam() === $team) {
                    $this->filter->writeCantAcceptOwnMatchMessage();
                }
                // Check Stop Loss
                elseif ($ladder->getStopLoss() > 0 && ($team->getLosses() + count($this->mapper['report']->findDisputedMatchIdsByTeam($team->getId()))) >= $ladder->getStopLoss()) {
                    $this->filter->writeStopLossMessage();
                }
                // Check locked team
                elseif ($ladder->isTeamLockRequired() && !$team->isLocked()) {
                    $this->filter->writeLockTeamMessage();
                }
                else {
                    // Verify Participating Members Eligibility
                    $this->delegate($this->command['eligibility'], [
                        'amount' => $match->getWager(),
                        'game' => $ladder->getGame(),
                        'membership' => $ladder->isMembershipRequired(),
                        'users' => $roster,
                        'wagers' => $match->isWager()
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
        }

        if (!$this->filter->hasErrors()) {
            $memo = "'{$ladder->getName()}' Wager Match With Team '{$team->getName()}'";
            $report = $this->mapper['report']->create([
                'match' => $match->getId(),
                'roster' => $roster,
                'team' => $team->getId(),
                'user' => $user
            ]);
            $reports = $this->mapper['report']->findByMatch($match->getId());
            $scheduleJob = false;

            if ($match->getTeamsPerMatch() > 2) {
                $count = $this->mapper['report']->countByMatch($match->getId());

                if ($count >= $match->getTeamsPerMatch()) {
                    $match->start();
                }
                elseif (!$match->isUpcoming()) {
                    $match->upcoming();
                    $scheduleJob = true;
                }
            }
            else {
                $match->start();
            }

            if ($match->isActive()) {
                $match->fill([
                    'hosts' => $this->service['host']->ladder(
                        $this->mapper['team']->findByIds(...array_merge($reports->column('team'), [$team->getId()])),
                        $match->getBestOf(),
                        $match->getTeamsPerMatch()
                    )
                ]);
            }

            $this->mapper['match']->transaction(function () use ($match, $memo, $report, $reports, $scheduleJob) {
                $this->mapper['report']->transaction(function (Closure $rollback) use ($match, $memo, $report, $reports) {
                    $this->mapper['report']->insert($report);
                    $this->mapper['report']->update(...iterator_to_array($reports));

                    // Charge Wager Fees
                    if ($match->isWager()) {
                        $this->delegate($this->command['charge'], [
                            'amount' => $match->getWager(),
                            'ladder' => $match->getLadder(),
                            'laddermatch' => $match->getId(),
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
                    return;
                }

                $this->mapper['match']->update($match);

                if ($scheduleJob) {
                    $this->mapper['match']->scheduleStartJob(['id' => $match->getId()], ($match->getStartedAt() - time()));
                }
            });
        }

        return $match;
    }
}
