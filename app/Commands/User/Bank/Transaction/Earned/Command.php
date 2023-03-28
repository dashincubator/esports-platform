<?php

namespace App\Commands\User\Bank\Transaction\Earned;

use App\Organization;
use App\Commands\AbstractCommand;
use App\DataSource\Game\Mapper as GameMapper;
use App\DataSource\User\Bank\Mapper as BankMapper;
use App\DataSource\User\Bank\Transaction\Mapper as TransactionMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(
        BankMapper $bank,
        Filter $filter,
        GameMapper $game,
        Organization $organization,
        TransactionMapper $transaction
    ) {
        $this->filter = $filter;
        $this->mapper = compact('bank', 'game', 'transaction');
        $this->organization = $organization;
    }


    protected function run(
        float $amount,
        ?int $game,
        ?int $ladder,
        ?int $ladderMatch,
        string $memo,
        ?int $team,
        ?int $tournament,
        array $users,
        ?bool $withdrawable
    ) : void
    {
        $transactions = [];
        $withdrawable = (bool) ($withdrawable ?? true);

        foreach ($users as $user) {
            $transaction = $this->mapper['transaction']->create(
                compact('amount', 'ladder', 'ladderMatch', 'memo', 'team', 'tournament', 'user')
            );
            $transaction->earned();

            $transactions[] = $transaction;
        }

        $this->mapper['bank']->transaction(function () use ($amount, $game, $transactions, $users, $withdrawable) {
            $banks = $this->mapper['bank']->findByOrganizationAndUsers($this->organization->getId(), ...$users);

            foreach ($banks as $bank) {
                $bank->earned($amount, $withdrawable);
            }

            $this->mapper['bank']->replace(...$banks);

            $this->mapper['transaction']->transaction(function () use ($transactions) {
                $this->mapper['transaction']->insert(...$transactions);
            });

            if ($game) {
                $this->mapper['game']->pushTotalPrizesPaidQueue($game, ($amount * count($users)));
            }
        });
    }
}
