<?php

namespace App\DataSource\User\Bank\Withdraw;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $amount;

    protected $createdAt;

    protected $email;

    protected $fee = 0;

    protected $id;

    protected $organization;

    protected $processedAt = 0;

    protected $processor;

    protected $processorTransactionId = '';

    protected $user;


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
