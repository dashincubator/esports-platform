<?php

namespace App\DataSource\User\Bank\Deposit;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $amount;

    protected $chargeback = false;

    protected $createdAt;

    protected $email = '';

    protected $fee = 0;

    protected $id;

    protected $organization;

    protected $processedAt = 0;

    protected $processor;

    protected $processorTransaction;

    protected $processorTransactionId;

    protected $user;

    protected $verifiedAt = 0;


    protected function getCasts() : array
    {
        return [
            'chargeback' => 'bool',
            'processorTransaction' => 'array'
        ];
    }


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
