<?php

namespace App\Commands\Game\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('game')
            ],
            'ids' => [
                'array' => $this->templates->invalid('games list')
            ],
            'ids.*' => [
                'int' => $this->templates->invalid('games list')
            ],
            'platform' => [
                'int' => $this->templates->invalid('platform')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Game(s) deleted successfully!';
    }
}
