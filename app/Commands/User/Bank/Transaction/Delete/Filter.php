<?php

namespace App\Commands\User\Bank\Transaction\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('transaction')
            ],
            'user' => [
                'int' => $this->templates->invalid('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Bank transaction(s) deleted successfully';
    }
}
