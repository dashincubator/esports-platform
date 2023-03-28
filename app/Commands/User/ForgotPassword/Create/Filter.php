<?php

namespace App\Commands\User\ForgotPassword\Create;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'email' => [
                'email' => $this->templates->invalid('email'),
                'required' => $this->templates->required('email')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return "An email has been sent with further instructions. Don't forget to check your spam folder";
    }
}
