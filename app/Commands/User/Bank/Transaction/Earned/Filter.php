<?php

namespace App\Commands\User\Bank\Transaction\Earned;

use App\Commands\User\Bank\Transaction\AbstractFilter;

class Filter extends AbstractFilter
{

    public function getRules(array $data = []) : array
    { 
        return array_merge(parent::getRules($data), [
            'game' => [
                'int' => $this->templates->invalid('game')
            ],
            'withdrawable' => [
                'bool' => $this->templates->invalid('withdrawable')
            ]
        ]);
    }
}
