<?php

namespace App\Commands\Ladder\Team\Update\Glicko2;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'ids' => [
                'array' => $this->templates->invalid('teams list'),
                'required' => $this->templates->required('teams list')
            ],
            'ids.*' => [
                'int' => $this->templates->invalid('teams list'),
                'required' => $this->templates->required('teams list')
            ]
        ];
    }
}
