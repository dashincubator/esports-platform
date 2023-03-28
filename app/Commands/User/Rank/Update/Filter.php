<?php

namespace App\Commands\User\Rank\Update;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'game' => [
                'int' => $this->templates->invalid('game'),
                'required' => $this->templates->required('game')
            ],
            'rosters' => [
                'array' => $this->templates->invalid('rosters list'),
                'required' => $this->templates->required('rosters list')
            ],
            'rosters.*' => [
                'array' => $this->templates->invalid('rosters list'),
                'required' => $this->templates->required('rosters list')
            ],
            'rosters.*.*' => [
                'int' => $this->templates->invalid('rosters list'),
                'required' => $this->templates->required('rosters list')
            ]
        ];
    }
}
