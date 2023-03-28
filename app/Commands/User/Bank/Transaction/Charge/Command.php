<?php

namespace App\Commands\User\Bank\Transaction\Charge;

use App\Organization;
use App\Commands\AbstractCommand;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Bank\Mapper as BankMapper;
use App\DataSource\User\Bank\Transaction\Mapper as TransactionMapper;

class Command extends AbstractCommand
{

    private $mapper;

    private $organization;


    public function __construct(
        BankMapper $bank,
        Filter $filter,
        Organization $organization,
        TransactionMapper $transaction,
        UserMapper $user
    ) {
        $this->filter = $filter;
        $this->mapper = compact('bank', 'transaction', 'user');
        $this->organization = $organization;
    }


    protected function run(
        float $amount,
        ?int $ladder,
        ?int $ladderMatch,
        string $memo,
        ?int $team,
        ?int $tournament,
        array $users
    ) : bool
    {
        $banks = $this->mapper['bank']->findByOrganizationAndUsers($this->organization->getId(), ...$users);
        $details = compact($this->filter->getFields(['users']));
        $transactions = [];
        $users = $this->mapper['user']->findByIds(...$users);

        foreach ($banks as $bank) {
            if (!$bank->charge($amount)) {
                $this->filter->writeInsufficientFundsMessage($users->get('id', $bank->getUser())->getUsername());
                continue;
            }

            $transaction = $this->mapper['transaction']->create(array_merge($details, [
                'user' => $bank->getUser()
            ]));
            $transaction->charge();

            $transactions[] = $transaction;
        }

        if (!$this->filter->hasErrors()) {
            $this->mapper['bank']->transaction(function() use ($banks, $transactions) {
                $this->mapper['bank']->replace(...$banks);

                $this->mapper['transaction']->transaction(function () use ($transactions) {
                    $this->mapper['transaction']->insert(...$transactions);
                });
            });
        }

        return !$this->filter->hasErrors();
    }
}
