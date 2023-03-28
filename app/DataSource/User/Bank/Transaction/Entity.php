<?php

namespace App\DataSource\User\Bank\Transaction;

use App\DataSource\AbstractEntity;

class Entity extends AbstractEntity
{

    private const TYPE_CREDIT = 0;

    private const TYPE_DEBIT = 1;


    protected $guarded = [
        'createdAt',
        'id',
        'refundedAt',
        'type'
    ];


    public function earned() : void
    {
        $this->set('type', self::TYPE_DEBIT);
    }


    public function charge() : void
    {
        $this->set('type', self::TYPE_CREDIT);
    }


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function isCharge() : bool
    {
        return $this->get('type') === self::TYPE_CREDIT;
    }


    public function refunded() : void
    {
        $this->set('refundedAt', time());
    }


    protected function setAmount(float $amount) : float
    {
        return round(abs($amount), 2);
    }


    public function toArray() : array
    {
        $data = parent::toArray();
        $data['isCharge'] = $this->isCharge();

        return $data;
    }
}
