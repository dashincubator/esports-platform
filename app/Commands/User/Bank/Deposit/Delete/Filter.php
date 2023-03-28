<?php

namespace App\Commands\User\Bank\Deposit\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('deposit')
            ],
            'user' => [
                'int' => $this->templates->invalid('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Bank deposit(s) deleted successfully';
    }
}
