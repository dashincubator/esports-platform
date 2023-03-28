<?php

namespace App\Commands\User\Bank\Transaction\Charge;

use App\Commands\User\Bank\Transaction\AbstractFilter;

class Filter extends AbstractFilter
{

    public function writeInsufficientFundsMessage(string $username) : void
    {
        $this->error("{$username} does not have enough funds to cover this transaction");
    }
}
