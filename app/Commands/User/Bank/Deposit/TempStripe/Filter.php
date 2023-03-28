<?php

namespace App\Commands\User\Bank\Deposit\TempStripe;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    public function getRules(array $data = []) : array
    {
        return [
            'amount' => [
                'numeric' => $this->templates->invalid('amount'),
                'required' => $this->templates->required('amount')
            ],
            'organization' => [
                'int' => $this->templates->invalid('organization'),
                'required' => $this->templates->required('organization')
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
