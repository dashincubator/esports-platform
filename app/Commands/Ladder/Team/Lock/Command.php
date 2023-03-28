<?php

namespace App\Commands\Ladder\Team\Lock;

use App\Organization;
use App\Commands\AbstractCommand;
use App\Commands\User\Bank\Transaction\Bill\Command as BankBillCommand;
use App\Commands\User\Bank\Transaction\Charge\Command as BankChargeCommand;
use App\Commands\User\Eligibility\Command as EligibilityCommand;
use App\DataSource\Ladder\Mapper as LadderMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\DataSource\Ladder\Team\Member\Mapper as TeamMemberMapper;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Account\Mapper as AccountMapper;
use App\DataSource\Game\Mapper as GameMapper;

class Command extends AbstractCommand
{

    private const ORGANIZATION_FEE = 1;

    private const ORGANIZATION_ID = 1;


    private $mapper;

    private $organization;


    public function __construct(
        AccountMapper $account,
        BankBillCommand $bill,
        BankChargeCommand $charge,
        EligibilityCommand $eligibility,
        Filter $filter,
        GameMapper $game,
        LadderMapper $ladder,
        Organization $organization,
        TeamMapper $team,
        TeamMemberMapper $member,
        UserMapper $user
    ) {
        $this->command = compact('bill', 'charge', 'eligibility');
        $this->filter = $filter;
        $this->mapper = compact('account', 'charge', 'game', 'ladder', 'member', 'team', 'user');
        $this->organization = $organization;
    }


    protected function run(int $id) : bool
    {
        $team = $this->mapper['team']->findById($id);

        if ($team->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif ($team->isLocked()) {
            $this->filter->writeTeamAlreadyLockedMessage();
        }
        else {
            $ladder = $this->mapper['ladder']->findById($team->getLadder());

            if ($ladder->isEmpty()) {
                $this->filter->writeUnknownErrorMessage();
            }
            elseif (!$ladder->isTeamLockRequired()) {
                $this->filter->writeLadderDoesNotRequireTeamLockMessage();
            }
            else {
                $count = $this->mapper['member']->countMembersOfTeam($team->getId());
                $max = $ladder->getMaxPlayersPerTeam();
                $min = $ladder->getMinPlayersPerTeam();

                if ($count < $min || $count > $max) {
                    $this->filter->writeInvalidRosterSizeMessage($min, $max);
                }
            }

            if (!$this->filter->hasErrors()) {
                $members = $this->mapper['member']->findByTeam($team->getId());

                // Verify Member Eligibility
                $this->delegate($this->command['eligibility'], [
                    'amount' => $ladder->getEntryFee(),
                    'game' => $ladder->getGame(),
                    'gameStat' => (!$ladder->isMatchFinderRequired()),
                    'membership' => $ladder->isMembershipRequired(),
                    'users' => $members->column('user')
                ]);
            }
        }

        if (!$this->filter->hasErrors()) {
            $this->mapper['team']->transaction(function() use ($members, $team, $ladder) {
                // Charge Entry Fees
                if ($ladder->getEntryFee() > 0) {
                    $memo = "{$this->organization->getName()} '{$ladder->getName()}' Entry Fee With Team '{$team->getName()}'";

                    $this->delegate($this->command['charge'], [
                        'amount' => $ladder->getEntryFee(),
                        'ladder' => $ladder->getId(),
                        'memo' => $memo,
                        'team' => $team->getId(),
                        'users' => $members->column('user')
                    ]);
                }

                if ($this->filter->hasErrors()) {
                    return;
                }

                if (!$ladder->isMatchFinderRequired()) {
                    $account = $this->mapper['game']->findById($ladder->getGame())->getAccount();
                    $users = $this->mapper['user']->findByIds(...$members->column('user'));

                    $accounts = $this->mapper['account']->findByNameAndUsers($account, ...$users->column('id'));

                    foreach ($users as $user) {
                        $members->get('user', $user->getId())->lock($accounts->findByUser($user->getId())->getFirstValue());
                    }

                    $this->mapper['member']->update(...iterator_to_array($members));
                }

                $team->lock();
                $this->mapper['team']->update($team);
            });
        }

        return $this->booleanResult();
    }
}
