<?php

namespace App\Commands\Game\Update;

use App\Commands\Game\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'banner' => [],
            'card' => [],
            'id' => [
                'int' => $this->templates->invalid('game'),
                'required' => $this->templates->required('game')
            ]
        ]);
    }


    protected function getSuccessMessage() : string
    {
        return 'Game updated successfully!';
    }
}
