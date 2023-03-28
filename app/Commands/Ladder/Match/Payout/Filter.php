<?php

namespace App\Commands\Ladder\Match\Payout;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'ladderMatch' => [
                'int' => $this->templates->invalid('ladder match'),
                'required' => $this->templates->required('ladder match')
            ],
            'team' => [
                'int' => $this->templates->invalid('team'),
                'required' => $this->templates->required('team')
            ],
            'users' => [
                'array' => $this->templates->invalid('user list'),
                'required' => $this->templates->required('user list')
            ],
            'users.*' => [
                'int' => $this->templates->invalid('user list'),
                'required' => $this->templates->required('user list')
            ]
        ];
    }


    public function writeMatchAlreadyPaidMessage() : void
    {
        $this->error("Match winnings has already been paid to user(s)");
    }
}
