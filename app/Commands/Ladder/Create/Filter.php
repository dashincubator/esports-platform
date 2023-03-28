<?php

namespace App\Commands\Ladder\Create;

use App\Commands\Ladder\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'game' => [
                'int' => $this->templates->invalid('game'),
                'required' => $this->templates->required('game')
            ],
        ]);
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('created');
    }
}
