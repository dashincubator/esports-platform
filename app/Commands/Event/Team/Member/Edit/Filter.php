<?php

namespace App\Commands\Event\Team\Member\Edit;

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
            'kick' => [
                'array' => $this->templates->invalid('kick list')
            ],
            'kick.*' => [
                'int' =>  $this->templates->invalid('kick list')
            ],
            'permissions' => [
                'array' =>  $this->templates->invalid('permissions list')
            ],
            'team' => [
                'int' => $this->templates->invalid('team'),
                'required' => $this->templates->required('team')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'All changes made successfully!';
    }
}
