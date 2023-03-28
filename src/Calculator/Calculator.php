<?php declare(strict_types=1);

namespace System\Calculator;

use Contracts\Calculator\Calculator as Contract;
use Decimal\Decimal;

class Calculator implements Contract
{

    public function abs($value) : string
    {
        return $this->wrap($value)->abs()->toString();
    }


    public function add($value1, $value2, ?int $places = null) : string
    {
        return $this->operation($value1, $value2, 'add', $places);
    }


    public function avg(array $values, ?int $places = null) : string
    {
        $value = $this->wrap()->avg(array_map(function($value) { return (string) $value; }, $values));

        if (!is_null($places)) {
            return $value->toFixed($places);
        }

        return $value->toString();
    }


    public function divide($value1, $value2, ?int $places = null) : string
    {
        return $this->operation($value1, $value2, 'div', $places);
    }


    public function isEqual($value1, $value2) : bool
    {
        return $this->wrap($value1)->equals((string) $value2);
    }


    public function isEven($value) : bool
    {
        return $this->wrap($value)->isEven();
    }


    public function isGreaterThan($value1, $value2) : bool
    {
        return $this->wrap($value1) > $this->wrap($value2);
    }


    public function isGreaterThanOrEqual($value1, $value2) : bool
    {
        return $this->wrap($value1) >= $this->wrap($value2);
    }


    public function isLessThan($value1, $value2) : bool
    {
        return $this->wrap($value1) < $this->wrap($value2);
    }


    public function isLessThanOrEqual($value1, $value2) : bool
    {
        return $this->wrap($value1) <= $this->wrap($value2);
    }


    public function isNegative($value) : bool
    {
        return $this->wrap($value)->isNegative();
    }


    public function isOdd($value) : bool
    {
        return $this->wrap($value)->isOdd();
    }


    public function isPositive($value) : bool
    {
        return $this->wrap($value)->isPositive();
    }


    public function multiply($value1, $value2, ?int $places = null) : string
    {
        return $this->operation($value1, $value2, 'mul', $places);
    }


    private function operation($value1, $value2, string $operation, ?int $places) : string
    {
        $value = $this->wrap($value1)->{$operation}((string) $value2);

        if (!is_null($places)) {
            return $value->toFixed($places);
        }

        return $value->toString();
    }


    public function round($value, int $places = 0, int $mode = Decimal::ROUND_HALF_EVEN) : string
    {
        return $this->wrap($value)->round($places, $mode);
    }


    public function subtract($value1, $value2, ?int $places = null) : string
    {
        return $this->operation($value1, $value2, 'sub', $places);
    }


    public function sum(array $values, ?int $places = null) : string
    {
        $value = $this->wrap()->sum(array_map(function($value) { return (string) $value; }, $values));

        if (!is_null($places)) {
            return $value->toFixed($places);
        }

        return $value->toString();
    }


    public function trim($value) : string
    {
        return $this->wrap($value)->trim()->toString();
    }


    private function wrap($value = 0) : Decimal
    {
        return new Decimal((string) $value);
    }
}
