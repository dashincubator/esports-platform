<?php

namespace App\Commands\Game\Platform\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('platform')
            ],
            'ids' => [
                'array' => $this->templates->invalid('platforms list')
            ],
            'ids.*' => [
                'int' => $this->templates->invalid('platforms list')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Platform(s) deleted successfully!';
    }
}
