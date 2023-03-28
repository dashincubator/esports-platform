<?php

namespace App\Commands\Organization\Update;

use App\Commands\Organization\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'id' => [
                'int' => $this->templates->invalid('organization'),
                'required' => $this->templates->required('organization')
            ],
        ]);
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('updated');
    }
}
