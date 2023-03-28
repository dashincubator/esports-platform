<?php

namespace App\Commands\User\Bank\Deposit;

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
            'fee' => [
                'numeric' => $this->templates->invalid('fee'),
                'required' => $this->templates->required('fee')
            ],
            'processor' => [
                'string' => $this->templates->string('payment processor'),
                'required' => $this->templates->required('payment processor')
            ],
            'processorTransaction' => [
                'array' => $this->templates->invalid('payment processor transaction'),
                'required' => $this->templates->required('payment processor transaction')
            ],
            'processorTransactionId' => [
                'string' => $this->templates->string('payment processor transaction id'),
                'required' => $this->templates->required('payment processor transaction id')
            ],
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    public function writeDuplicateTransactionMessage() : void
    {
        $this->error("Payment processor transaction ID already exists in DB");
    }
}
