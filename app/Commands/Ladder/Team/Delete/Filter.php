<?php

namespace App\Commands\Ladder\Team\Delete;

use App\Commands\Event\Team\Delete\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('team')
            ],
            'ladder' => [
                'int' => $this->templates->invalid('ladder')
            ]
        ];
    }
}
