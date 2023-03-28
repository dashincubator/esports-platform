<?php

namespace App\Commands\User\Bank\Transaction;

use App\Commands\AbstractFilter as AbstractParent;

abstract class AbstractFilter extends AbstractParent
{

    protected function getRules(array $data = []) : array
    {
        $amount = $data['amount'] ?? 0;

        return [
            'amount' => [
                'min:0' => $this->templates->min('amount', $amount, 0),
                'numeric' => $this->templates->invalid('amount'),
                'required' => $this->templates->required('amount')
            ],
            'ladder' => [
                'int' => $this->templates->invalid('ladder')
            ],
            'ladderMatch' => [
                'int' => $this->templates->invalid('ladder match')
            ],
            'memo' => [
                'required' => $this->templates->required('bank memo'),
                'string' => $this->templates->string('bank memo')
            ],
            'team' => [
                'int' => $this->templates->invalid('team')
            ],
            'tournament' => [
                'int' => $this->templates->invalid('tournament')
            ],
            'users' => [
                'array' => $this->templates->invalid('users list'),
                'required' => $this->templates->required('users list')
            ],
            'users.*' => [
                'int' => $this->templates->invalid('users list'),
                'required' => $this->templates->required('users list')
            ]
        ];
    }
}
