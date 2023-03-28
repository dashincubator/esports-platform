<?php

namespace App\Commands\User\AdminPosition\Create;

use App\Commands\User\AdminPosition\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'organization' => [
                'int' => $this->templates->invalid('organization'),
                'required' => $this->templates->required('organization')
            ]
        ]);
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('created');
    }
}
