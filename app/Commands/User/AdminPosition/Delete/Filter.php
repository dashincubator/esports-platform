<?php

namespace App\Commands\User\AdminPosition\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'editor' => [
                'int' => $this->templates->invalid('editor'),
                'required' => $this->templates->required('editor')
            ],
            'id' => [
                'int' => $this->templates->invalid('admin position')
            ],
            'organization' => [
                'int' => $this->templates->invalid('organization')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Admin position(s) deleted successfully!';
    }
}
