<?php

namespace App\Commands\Ladder\Update;

use App\Commands\Ladder\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'banner' => [],
            'card' => [],
            'id' => [
                'int' => $this->templates->invalid('ladder'),
                'required' => $this->templates->required('ladder')
            ]
        ]);
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('updated');
    }
}
