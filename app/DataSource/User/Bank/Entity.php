<?php

namespace App\DataSource\User\Bank;

use App\DataSource\AbstractEntity;
use Contracts\Calculator\Calculator;

class Entity extends AbstractEntity
{

    private const DECIMAL_PLACES = 2;


    private $calculator;


    public function __construct(Calculator $calculator, Record $record)
    {
        parent::__construct($record);

        $this->calculator = $calculator;
    }


    public function bill(float $amount) : bool
    {
        $this->set('balance', $this->calculator->subtract($this->get('balance'), $amount, self::DECIMAL_PLACES));
        $this->set('withdrawable', $this->calculator->subtract($this->get('withdrawable'), $amount, self::DECIMAL_PLACES));

        if ($this->calculator->isLessThan($this->get('withdrawable'), 0)) {
            $this->set('withdrawable', 0);
        }

        return true;
    }


    public function charge(float $amount) : bool
    {
        if ($this->calculator->isGreaterThanOrEqual($this->get('balance'), $amount)) {
            $this->set('balance', $this->calculator->subtract($this->get('balance'), $amount, self::DECIMAL_PLACES));
            $this->set('withdrawable', $this->calculator->subtract($this->get('withdrawable'), $amount, self::DECIMAL_PLACES));

            if ($this->calculator->isLessThan($this->get('withdrawable'), 0)) {
                $this->set('withdrawable', 0);
            }

            return true;
        }

        return false;
    }


    public function deposit(float $amount) : void
    {
        $this->set('balance', $this->calculator->add(
            $this->get('balance'),
            $amount,
            self::DECIMAL_PLACES
        ));
    }


    public function earned(float $amount, bool $withdrawable = true) : void
    {
        $this->set('balance', $this->calculator->add($this->get('balance'), $amount, self::DECIMAL_PLACES));

        if ($withdrawable) {
            $this->set('withdrawable', $this->calculator->add($this->get('withdrawable'), $amount, self::DECIMAL_PLACES));
        }
    }


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function isChargeable(float $amount) : bool
    {
        return $this->calculator->isGreaterThanOrEqual($this->get('balance'), $amount);
    }


    public function refund(float $amount) : void
    {
        $this->earned($amount);
    }


    public function withdraw(float $amount) : bool
    {
        if ($this->calculator->isGreaterThanOrEqual($this->get('withdrawable'), $amount)) {
            return $this->charge($amount);
        }

        return false;
    }
}
