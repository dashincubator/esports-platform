<?php

namespace App\Commands\Event\Team\Invite\FindBy;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'column' => [
                'required' => $this->templates->required('find by key'),
                'string' => $this->templates->string('find by key')
            ],
            'team' => [
                'int' => $this->templates->invalid('team'),
                'required' => $this->templates->required('team')
            ],
            'value' => [
                'required' => $this->templates->required('find by value')
            ]
        ];
    }


    protected function getSuccessMessage(string $username = '') : string
    {
        return "Team invite sent to {$username} successfully!";
    }
}
