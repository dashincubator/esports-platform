<?php

namespace App\Commands\Ladder\Payout;

use App\Organization;
use App\Commands\AbstractCommand;
use App\Commands\Ladder\Delete\Command as LadderDeleteCommand;
use App\Commands\User\Bank\Transaction\Earned\Command as BankEarnedCommand;
use App\Commands\User\Membership\Earned\Command as MembershipEarnedCommand;
use App\DataSource\Ladder\Mapper as LadderMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\DataSource\Ladder\Team\Member\Mapper as MemberMapper;
use App\DataSource\User\Bank\Transaction\Mapper as TransactionMapper;
use App\View\Extensions\Ordinal;
use Contracts\Calculator\Calculator;

class Command extends AbstractCommand
{

    private const BANK_DECIMAL_PLACES = 2;


    private $calculator;

    private $command;

    private $mapper;

    private $ordinal;

    private $organization;


    public function __construct(
        BankEarnedCommand $earnedAmount,
        Calculator $calculator,
        LadderDeleteCommand $ladderDelete,
        MembershipEarnedCommand $earnedMembership,
        Filter $filter,
        LadderMapper $ladder,
        MemberMapper $member,
        Ordinal $ordinal,
        Organization $organization,
        TeamMapper $team,
        TransactionMapper $transaction
    ) {
        $this->calculator = $calculator;
        $this->command = [
            'earned.amount' => $earnedAmount,
            'earned.membership' => $earnedMembership,
            'ladder.delete' => $ladderDelete
        ];
        $this->filter = $filter;
        $this->mapper = compact('ladder', 'member', 'team', 'transaction');
        $this->ordinal = $ordinal;
        $this->organization = $organization;
    }


    protected function run(int $id, array $payout) : bool
    {
        $ladder = $this->mapper['ladder']->findById($id);
        $teams = array_unique(array_column($payout, 'team'));

        if ($ladder->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif (count($payout) !== count($teams)) {
            $this->filter->writeDuplicateTeamsFoundMessage();
        }
        else {
            $teams = $this->mapper['team']->findByIds(...$teams);

            if (count($payout) !== count($teams)) {
                $this->filter->writeInvalidTeamMessage();
            }

            $invalid = $teams->filter(function($entity) use ($ladder) {
                return $entity->getLadder() !== $ladder->getId();
            });

            if (!$invalid->isEmpty()) {
                $this->filter->writeInvalidTeamMessage(...$invalid->column('id'));
            }
        }

        if (!$this->filter->hasErrors()) {
            foreach ($payout as $detail) {
                $team = $teams->get('id', (int) $detail['team']);

                // Look For Paid Transactions Just In Case
                if (!$this->mapper['transaction']->findPayoutsByLadderAndTeam($ladder->getId(), $team->getId())->isEmpty()) {
                    continue;
                }

                $members = $this->mapper['member']->findByTeam($team->getId());

                if (!$this->filter->hasErrors() && $detail['amount'] > 0) {
                    $amount = $this->calculator->divide($detail['amount'], count($members), self::BANK_DECIMAL_PLACES);
                    $memo = "Placed {$detail['place']}" . ($this->ordinal)($detail['place']) . " in {$this->organization->getName()} '{$ladder->getName()}' with '{$team->getName()}' team";

                    $this->delegate($this->command['earned.amount'], [
                        'amount' => $amount,
                        'game' => $ladder->getGame(),
                        'ladder' => $ladder->getId(),
                        'memo' => $memo,
                        'team' => $team->getId(),
                        'users' => $members->column('user')
                    ]);
                }

                if (!$this->filter->hasErrors() && $detail['membership'] > 0) {
                    $this->delegate($this->command['earned.membership'], [
                        'days' => $detail['membership'],
                        'users' => $members->column('user')
                    ]);
                }
            }
        }

        if (!$this->filter->hasErrors()) {
            $this->delegate($this->command['ladder.delete'], [
                'id' => $ladder->getId()
            ]);
        }

        return $this->booleanResult();
    }
}
