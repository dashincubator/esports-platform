<?php declare(strict_types=1);

namespace System\Collections;

use Contracts\Collections\Stack as Contract;

class Stack implements Contract
{

    protected $values = [];


    public function add($value) : void
    {
        array_unshift($this->values, $value);
    }


    public function clear() : void
    {
        $this->values = [];
    }


    public function count() : int
    {
        return count($this->values);
    }


    public function has($value) : bool
    {
        return array_search($value, $this->values) !== false;
    }


    public function next()
    {
        if (count($this->values) === 0) {
            return null;
        }

        return array_shift($this->values);
    }


    public function peek()
    {
        return $this->values[0] ?? null;
    }


    public function toArray() : array
    {
        return $this->values;
    }
}
