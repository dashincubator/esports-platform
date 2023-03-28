<?php

namespace App\Commands\User\ForgotPassword\ResetPassword;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        // Vague messages for code, id to make phishing harder
        return [
            'code' => [
                'required' => $this->templates->required('forgot password token'),
                'string' => $this->templates->string('forgot password token')
            ],
            'id' => [
                'int' => $this->templates->invalid('forgot password token'),
                'required' => $this->templates->required('forgot password token')
            ],
            'password' => [
                'required' => $this->templates->required('password'),
                'string' => $this->templates->string('password')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Password updated successfully!';
    }


    public function writeInvalidTokenMessage() : void
    {
        $this->error('Forgot password token is invalid');
    }
}
