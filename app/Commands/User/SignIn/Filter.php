<?php

namespace App\Commands\User\SignIn;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'identifier' => [
                'required' => $this->templates->required('email or username'),
                'string' => $this->templates->string('email or username')
            ],
            'password' => [
                'required' => $this->templates->required('password'),
                'string' => $this->templates->string('password')
            ]
        ];
    }


    public function writeInvalidCredentialsMessage() : void
    {
        $this->error("Invalid username or password");
    }
}
