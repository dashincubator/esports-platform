<?php

namespace App\Commands\User\Bank\Deposit\Create;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'amount' => [
                'numeric' => $this->templates->invalid('amount'),
                'required' => $this->templates->required('amount')
            ]
        ];
    }


    public function writeInvalidDepositAmountMessage(float $minimum) : void
    {
        $this->flash->error("Deposits must exceed ${$minimum}");
    }
}
