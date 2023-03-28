<?php declare(strict_types=1);

namespace System\Http;

use ArrayIterator;
use Contracts\Http\{Response, ResponseCollection as Contract};
use Exception;

class ResponseCollection implements Contract
{

    private $values = [];


    public function count() : int
    {
        return count($this->values);
    }


    public function get(string $key) : Response
    {
        return $this->values[$key];
    }


    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->values);
    }


    public function has(string $key) : bool
    {
        return array_key_exists($key, $this->values);
    }


    public function set(string $key, Response $response) : Contract
    {
        $this->values[$key] = $response;

        return $this;
    }


    public function toArray() : array
    {
        return $this->values;
    }
}
