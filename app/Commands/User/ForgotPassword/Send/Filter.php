<?php

namespace App\Commands\User\ForgotPassword\Send;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('forgot password'),
                'required' => $this->templates->required('forgot password')
            ]
        ];
    }


    public function writeAlreadyEmailedMessage() : void
    {
        $this->error('Forgot password email already sent');
    }


    protected function getSuccessMessage() : string
    {
        return 'Forgot password email sent successfully!';
    }
}
