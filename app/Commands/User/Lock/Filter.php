<?php

namespace App\Commands\User\Lock;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'content' => [
                'array' => $this->templates->string('content'),
                'required' => $this->templates->required('content')
            ],
            'content.*' => [
                'required' => $this->templates->required('content'),
                'string' => $this->templates->string('content')
            ],
            'id' => [
                'int' => $this->templates->string('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }
}
