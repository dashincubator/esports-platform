<?php

namespace App\DataSource\User\Bank\Deposit;

use App\DataSource\AbstractEntity;

class Entity extends AbstractEntity
{

    protected $guarded = [
        'chargeback',
        'id',
        'processedAt'
    ];


    public function isAlreadyProcessed() : bool
    {
        return $this->get('processedAt') > 0;
    }


    public function chargeback() : void
    {
        $this->set('chargeback', true);
    }


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function processed() : void
    {
        $this->set('processedAt', time());
    }


    public function verified() : void
    {
        $this->set('verifiedAt', time());
    }
}
