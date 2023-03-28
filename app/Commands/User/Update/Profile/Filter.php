<?php

namespace App\Commands\User\Update\Profile;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'accounts' => [
                'array' => $this->templates->invalid('accounts')
            ],
            'avatar' => [],
            'banner' => [],
            'bio' => [
                'string' => $this->templates->string('bio')
            ],
            'email' => [
                'email' => $this->templates->invalid('email')
            ],
            'id' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ],
            'timezone' => [
                'timezone' => $this->templates->invalid('timezone')
            ],
            'wagers' => [
                'bool' => $this->templates->invalid('wagers switch')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Account updated successfully!';
    }


    public function writeEmailUnavailableMessage() : void
    {
        $this->error('Email is already in use');
    }
}
