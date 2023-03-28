<?php declare(strict_types=1);

namespace System\Collections;

use Contracts\Collections\Sequential as Contract;
use ArrayIterator;

class Sequential implements Contract
{

    protected $values = [];


    public function __construct(array $values = [])
    {
        $this->replace($values);
    }


    public function clear() : void
    {
        $this->values = [];
    }


    public function count() : int
    {
        return count($this->values);
    }


    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->values);
    }


    public function has($value) : bool
    {
        return array_search($value, $this->values) !== false;
    }


    public function intersect(array $values) : array
    {
        $found = [];

        foreach ($values as $value) {
            if (!$this->has($value)) {
                continue;
            }

            $found[$value] = $this->get($value);
        }

        return $found;
    }


    public function merge(array $value) : void
    {
        $this->values = array_merge($this->values, $value);
    }


    public function prepend($value) : void
    {
        array_unshift($this->values, $value);
    }


    public function pop()
    {
        return array_pop($this->values);
    }


    public function push($value) : void
    {
        $this->values[] = $value;
    }


    public function replace(array $values) : void
    {
        $this->values = $values;
    }


    public function toArray() : array
    {
        return $this->values;
    }
}
