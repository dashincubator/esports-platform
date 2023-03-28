<?php

namespace App\Commands\Game\Create;

use App\Commands\Game\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'platform' => [
                'int' => $this->templates->invalid('platform'),
                'required' => $this->templates->required('platform')
            ]
        ]);
    }


    protected function getSuccessMessage() : string
    {
        return 'Game created successfully!';
    }
}
