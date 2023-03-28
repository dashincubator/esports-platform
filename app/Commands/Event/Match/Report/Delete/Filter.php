<?php

namespace App\Commands\Event\Match\Report\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('match report')
            ],
            'match' => [
                'int' => $this->templates->invalid('match')
            ],
            'matches' => [
                'array' => $this->templates->invalid('match list')
            ],
            'matches.*' => [
                'int' => $this->templates->invalid('match list')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Match report(s) deleted successfully';
    }
}
