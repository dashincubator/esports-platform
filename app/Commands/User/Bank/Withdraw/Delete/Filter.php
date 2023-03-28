<?php

namespace App\Commands\User\Bank\Withdraw\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('withdraw')
            ],
            'user' => [
                'int' => $this->templates->invalid('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Bank withdraw(s) deleted successfully!';
    }
}
