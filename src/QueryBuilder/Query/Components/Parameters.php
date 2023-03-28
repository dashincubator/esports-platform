<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class Parameters
{

    private $values = [];


    public function add(array $values) : void
    {
        foreach ($values as $value) {
            if (is_array($value)) {
                $this->add($value);
            }
            else {
                $this->values[] = $value;
            }
        }
    }


    public function get() : array
    {
        return $this->values;
    }


    public function reset() : void
    {
        $this->values = [];
    }
}
