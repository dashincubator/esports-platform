<?php

namespace App\Commands\User\Bank\Deposit\Process;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('bank deposit'),
                'required' => $this->templates->required('bank deposit')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return "Bank deposit processed successfully!";
    }


    public function writeAlreadyProcessedMessage(int $id) : void
    {
        $this->error("Bank deposit {$id} was already deposited into user account");
    }
}
