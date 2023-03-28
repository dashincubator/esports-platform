<?php

namespace App\Commands\Game\Api\Match\Create;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'activision' => [
                'string' => $this->templates->string('activision')
            ],
            'playstation' => [
                'string' => $this->templates->string('playstation')
            ],
            'xbox' => [
                'string' => $this->templates->string('xbox')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return "Game stat(s) created successfully!";
    }
}
