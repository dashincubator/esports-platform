<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  "Safe" Number Calculator
 *
 */

namespace Contracts\Calculator;

use Decimal\Decimal;

interface Calculator
{

    /**
     *  Absolute Value Of '$value'
     *
     *  @param float|int|string $value
     *  @return string
     */
    public function abs($value) : string;


    /**
     *  Add '$value1' And '$value2'
     *
     *  @param float|int|string $value1
     *  @param float|int|string $value2
     *  @param int|null $places Decimal Places
     *  @return string
     */
    public function add($value1, $value2, ?int $places = null) : string;


    /**
     *  Return Avg Of Values
     *
     *  @param array $values
     *  @param int|null $places Decimal Places
     *  @return string
     */
    public function avg(array $values, ?int $places = null) : string;


    /**
     *  Divide '$value1' By '$value2'
     *
     *  @param float|int|string $value1
     *  @param float|int|string $value2
     *  @param int|null $places Decimal Places
     *  @return string
     */
    public function divide($value1, $value2, ?int $places = null) : string;


    /**
     *  @param float|int|string $value1
     *  @param float|int|string $value2
     *  @return bool True If Values Are The Same, Otherwise False
     */
    public function isEqual($value1, $value2) : bool;


    /**
     *  @param float|int|string $value
     *  @return bool True If '$value' Is Even, Otherwise False
     */
    public function isEven($value) : bool;


    /**
     *  @param float|int|string $value1
     *  @param float|int|string $value2
     *  @return bool True If '$value1' > '$value2', Otherwise False
     */
    public function isGreaterThan($value1, $value2) : bool;


    /**
     *  @param float|int|string $value1
     *  @param float|int|string $value2
     *  @return bool True If '$value1' >= '$value2', Otherwise False
     */
    public function isGreaterThanOrEqual($value1, $value2) : bool;


    /**
     *  @param float|int|string $value1
     *  @param float|int|string $value2
     *  @return bool True If '$value1' < '$value2', Otherwise False
     */
    public function isLessThan($value1, $value2) : bool;


    /**
     *  @param float|int|string $value1
     *  @param float|int|string $value2
     *  @return bool True If '$value1' <= '$value2', Otherwise False
     */
    public function isLessThanOrEqual($value1, $value2) : bool;


    /**
     *  @param float|int|string $value
     *  @return bool True If '$value' Is Negative, Otherwise False
     */
    public function isNegative($value) : bool;


    /**
     *  @param float|int|string $value
     *  @return bool True If '$value' Is Odd, Otherwise False
     */
    public function isOdd($value) : bool;


    /**
     *  @param float|int|string $value
     *  @return bool True If '$value' Is Positive, Otherwise False
     */
    public function isPositive($value) : bool;


    /**
     *  Multiply '$value1' By '$value2'
     *
     *  @param float|int|string $value1
     *  @param float|int|string $value2
     *  @param int|null $places Decimal Places
     *  @return string
     */
    public function multiply($value1, $value2, ?int $places = null) : string;


    /**
     *  Round Value
     *
     *  @param float|int|string $value
     *  @param int $places Decimal Places
     *  @param int $mode
     *  @return string
     */
    public function round($value, int $places = 0, int $mode = Decimal::ROUND_HALF_EVEN) : string;


    /**
     *  Subtract '$value2' From '$value1'
     *
     *  @param float|int|string $value1
     *  @param float|int|string $value2
     *  @param int|null $places Decimal Places
     *  @return string
     */
    public function subtract($value1, $value2, ?int $places = null) : string;


    /**
     *  Add All Values Together In Array
     *
     *  @param array $values
     *  @param int|null $places Decimal Places
     *  @return string
     */
    public function sum(array $values, ?int $places = null) : string;


    /**
     *  Remove Trailing Zeros
     *
     *  @param float|int|string $value
     *  @return string
     */
    public function trim($value) : string;
}
