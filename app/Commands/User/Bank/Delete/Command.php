<?php

namespace App\Commands\User\Bank\Delete;

use App\Commands\AbstractCommand;
use App\Commands\User\Bank\Deposit\Delete\Command as DeleteDepositCommand;
use App\Commands\User\Bank\Transaction\Delete\Command as DeleteTransactionCommand;
use App\Commands\User\Bank\Withdraw\Delete\Command as DeleteWithdrawCommand;

class Command extends AbstractCommand
{

    private $command;


    public function __construct(
        DeleteDepositCommand $deposit,
        DeleteTransactionCommand $transaction,
        DeleteWithdrawCommand $withdraw,
        Filter $filter
    ) {
        $this->command = compact('deposit', 'transaction', 'withdraw');
        $this->filter = $filter;
    }


    protected function run(int $user) : bool
    {
        foreach (['deposit', 'transaction', 'withdraw'] as $command) {
            $this->delegate($this->command[$command], compact('user'));
        }

        return $this->booleanResult();
    }
}
