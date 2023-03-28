<?php

namespace App\Commands\User\Bank\Transaction\Refund;

use App\Organization;
use App\Commands\AbstractCommand;
use App\DataSource\User\Bank\Mapper as BankMapper;
use App\DataSource\User\Bank\Transaction\Mapper as TransactionMapper;

class Command extends AbstractCommand
{

    private $mapper;

    private $organization;


    public function __construct(BankMapper $bank, Filter $filter, Organization $organization, TransactionMapper $transaction)
    {
        $this->filter = $filter;
        $this->mapper = compact('bank', 'transaction');
        $this->organization = $organization;
    }


    protected function run(array $ids) : void
    {
        $transactions = $this->mapper['transaction']->findRefundableByIds($ids);

        if ($transactions->isEmpty()) {
            return;
        }

        $users = $transactions->column('user');

        $this->mapper['bank']->transaction(function() use ($transactions, $users) {
            $banks = $this->mapper['bank']->findByOrganizationAndUsers($this->organization->getId(), ...$users);

            foreach ($transactions as $transaction) {
                $bank = $bank->get('user', $transaction->getUser());
                $bank->refund($transaction->getAmount());
            }

            $this->mapper['bank']->replace(...iterator_to_array($banks));

            $this->mapper['transaction']->transaction(function () use ($transactions) {
                foreach ($transactions as $transaction) {
                    $transaction->refunded();
                }

                $this->mapper['transaction']->update(...$transactions);
            });
        });
    }
}
