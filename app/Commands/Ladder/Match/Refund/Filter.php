<?php

namespace App\Commands\Ladder\Match\Cancel;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'ladderMatch' => [
                'int' => $this->templates->invalid('ladder match'),
                'required' => $this->templates->required('ladder match')
            ]
        ];
    }
}
