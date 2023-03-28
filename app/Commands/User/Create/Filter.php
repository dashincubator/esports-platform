<?php

namespace App\Commands\User\Create;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        $username = $data['username'] ?? '';

        return [
            'email' => [
                'email' => $this->templates->invalid('email'),
                'required' => $this->templates->required('email')
            ],
            'password' => [
                'required' => $this->templates->required('password'),
                'string' => $this->templates->string('password')
            ],
            'timezone' => [
                'required' => $this->templates->required('timezone'),
                'timezone' => $this->templates->invalid('timezone')
            ],
            'username' => [
                'max:20' => $this->templates->max('username', $username, 20),
                'min:3' => $this->templates->min('username', $username, 3),
                'required' => $this->templates->required('username'),
                'string' => $this->templates->string('username')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Account created successfully!';
    }


    public function writeUsernameUnavailableMessage() : void
    {
        $this->error('Username is already in use');
    }


    public function writeEmailUnavailableMessage() : void
    {
        $this->error('Email is already in use');
    }
}
