<?php

namespace App\Commands\User\ForgotPassword\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Forgot passwords deleted successfully!';
    }
}
