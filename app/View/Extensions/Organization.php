<?php

namespace App\View\Extensions;

use App\Organization as Proxy;
use Contracts\Support\Arrayable;

class Organization implements Arrayable
{

    private $proxy;


    public function __call(string $method, array $args)
    {
        return $this->proxy->{$method}(...$args);
    }


    public function __construct(Proxy $proxy)
    {
        $this->proxy = $proxy;
    }


    public function toArray() : array
    {
        return $this->proxy->toArray();
    }
}
