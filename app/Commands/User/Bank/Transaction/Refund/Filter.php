<?php

namespace App\Commands\User\Bank\Transaction\Refund;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'ids' => [
                'array' => $this->templates->invalid('transactions list'),
                'required' => $this->templates->required('transactions list')
            ],
            'ids.*' => [
                'int' => $this->templates->invalid('transactions list'),
                'required' => $this->templates->required('transactions list')
            ]
        ];
    }
}
