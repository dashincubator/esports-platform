<?php

namespace App\Commands\User\Rank\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('rank')
            ],
            'user' => [
                'int' => $this->templates->invalid('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Account ranks deleted successfully!';
    }
}
