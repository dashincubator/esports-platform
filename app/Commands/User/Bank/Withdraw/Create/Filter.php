<?php

namespace App\Commands\User\Bank\Withdraw\Create;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'amount' => [
                'numeric' => $this->templates->invalid('amount'),
                'required' => $this->templates->required('amount')
            ],
            'email' => [
                'email' => $this->templates->invalid('email'),
                'required' => $this->templates->required('email')
            ],
            'processor' => [
                'required' => $this->templates->required('payment processor'),
                'string' => $this->templates->string('payment processor')
            ],
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Withdraw request created successfully!';
    }


    public function writeChargeFailedMessage(float $amount) : void
    {
        $this->error('You do not have enough funds to withdraw $' . $amount);
    }
}
