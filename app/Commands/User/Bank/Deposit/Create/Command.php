<?php

namespace App\Commands\User\Bank\Deposit\Create;

use App\Commands\AbstractCommand;

class Command extends AbstractCommand
{

    private $mapper;

    private $minimum;


    public function __construct(Filter $filter, float $minimum)
    {
        $this->filter = $filter;
        $this->minimum = $minimum;
    }


    protected function run(float $amount) : bool
    {
        if ($amount < $this->minimum) {
            $this->responder->writeInvalidDepositAmountMessage($this->minimum);
        }

        return !$this->filter->hasErrors();
    }
}
