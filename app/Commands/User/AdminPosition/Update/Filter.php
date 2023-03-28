<?php

namespace App\Commands\User\AdminPosition\Update;

use App\Commands\User\AdminPosition\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'id' => [
                'int' => $this->templates->invalid('admin position'),
                'required' => $this->templates->required('admin position')
            ],
        ]);
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('updated');
    }
}
