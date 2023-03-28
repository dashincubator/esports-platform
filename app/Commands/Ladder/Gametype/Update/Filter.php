<?php

namespace App\Commands\Ladder\Gametype\Update;

use App\Commands\Ladder\Gametype\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'id' => [
                'int' => $this->templates->invalid('gametype'),
                'required' => $this->templates->required('gametype')
            ]
        ]);
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('updated');
    }
}
