<?php

namespace App\DataSource\User\Bank\Withdraw;

use App\DataSource\AbstractEntity;

class Entity extends AbstractEntity
{

    protected $fillable = [
        'amount', 'email', 'organization', 'processor', 'user'
    ];


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function processed(float $fee, string $transactionId) : void
    {
        $this->set('fee', $fee);
        $this->set('processedAt', time());
        $this->set('processorTransactionId', $transactionId);
    }
}
