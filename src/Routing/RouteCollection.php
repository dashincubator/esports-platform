<?php declare(strict_types=1);

namespace System\Routing;

use Contracts\Routing\{Route, RouteCollection as Contract};
use ArrayIterator;
use Exception;

class RouteCollection implements Contract
{

    private $values = [];


    public function __clone()
    {
        foreach ($this->values as $name => $route) {
            $this->values[$name] = clone $route;
        }
    }


    public function count() : int
    {
        return count($this->values);
    }


    public function get(string $name) : Route
    {
        if (!array_key_exists($name, $this->values)) {
            throw new Exception("'{$name}' Does Not Exist In Route Collection");
        }

        return $this->values[$name];
    }


    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->values);
    }


    public function has(string $name) : bool
    {
        return array_key_exists($name, $this->values);
    }


    public function set(string $name, Route $route) : Contract
    {
        $this->values[$name] = $route;

        return $this;
    }


    public function toArray() : array
    {
        return array_values($this->values);
    }
}
