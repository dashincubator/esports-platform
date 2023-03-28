<?php

namespace App\Commands\Ladder\Match\Update;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('match'),
                'required' => $this->templates->required('match')
            ]
        ];
    }
}
