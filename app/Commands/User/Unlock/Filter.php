<?php

namespace App\Commands\User\Unlock;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->string('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Account unlocked successfully!';
    }
}
