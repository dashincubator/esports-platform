<?php

namespace App\Commands\Event\Team\Update;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'avatar' => [],
            'banner' => [],
            'bio' => [
                'string' => $this->templates->string('bio')
            ],
            'id' => [
                'int' => $this->templates->invalid('team'),
                'required' => $this->templates->required('team')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Team updated successfully!';
    }
}
