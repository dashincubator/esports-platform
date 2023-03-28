<?php declare(strict_types=1);

namespace System\View\Extensions;

use Contracts\View\Factory;
use Contracts\View\Extensions\Extensions as Contract;
use Exception;

class Extensions implements Contract
{

    private $lazy = [];

    private $loaded = [];


    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }


    public function offsetExists($key) : bool
    {
        return array_key_exists($key, $this->lazy) || array_key_exists($key, $this->loaded);
    }


    public function offsetGet($key)
    {
        if (!array_key_exists($key, $this->loaded) && array_key_exists($key, $this->lazy)) {
            $this->loaded[$key] = $this->factory->createExtension($this->lazy[$key]);
        }

        return $this->loaded[$key];
    }


    public function offsetSet($key, $value) : void
    { }


    public function offsetUnset($key) : void
    { }


    public function add(string $key, string $classname) : void
    {
        $this->lazy[$key] = $classname;
    }
}
