<?php

namespace App\Commands\Event\Team\Member\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('team member')
            ],
            'team' => [
                'int' => $this->templates->invalid('team')
            ],
            'teams' => [
                'array' => $this->templates->invalid('teams list')
            ],
            'teams.*' => [
                'int' => $this->templates->invalid('teams list')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Team member(s) deleted successfully';
    }
}
