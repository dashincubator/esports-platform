<?php

namespace App\Commands\Ladder\Match\Delete;

use App\Commands\Event\Match\Delete\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('match')
            ],
            'ladder' => [
                'int' => $this->templates->invalid('ladder')
            ]
        ];
    }
}
