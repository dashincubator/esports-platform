<?php

namespace App\Commands\Ladder\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'game' => [
                'int' => $this->templates->invalid('game')
            ],
            'id' => [
                'int' => $this->templates->invalid('ladder')
            ],
            'organization' => [
                'int' => $this->templates->invalid('organization')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Ladder(s) deleted successfully!';
    }
}
