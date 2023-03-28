<?php

namespace App\Commands\User\Update\Password;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'current' => [
                'required' => $this->templates->required('current password'),
                'string' => $this->templates->string('current password')
            ],
            'id' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ],
            'new' => [
                'required' => $this->templates->required('new password'),
                'string' => $this->templates->string('new password')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Password updated successfully!';
    }


    public function writeInvalidPasswordMessage()
    {
        $this->error("The password you provided does not match the one associated with this account");
    }
}
