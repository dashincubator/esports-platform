<?php

namespace App\Commands\User\Bank\Withdraw\Process;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('withdraw'),
                'required' => $this->templates->required('withdraw')
            ],
            'processorTransactionId' => [
                'required' => $this->templates->required('payment processor id'),
                'string' => $this->templates->string('payment processor id')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Withdraw marked as processed!';
    }


    public function writeDuplicateTransactionMessage() : void
    {
        $this->error("Payment processor transaction ID was already used for another transaction");
    }
}
