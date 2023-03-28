<?php declare(strict_types=1);

namespace System\Http;

use Contracts\Collections\Associative as Collection;
use ArrayIterator;

abstract class AbstractHeaders
{

    // Collection Instance
    protected $headers;


    public function __construct(Collection $headers)
    {
        $this->headers = $headers;
    }


    public function add(string $name, $value, bool $replace = true) : void
    {
        $this->set($name, $value, $replace);
    }


    public function delete(string $name) : void
    {
        $this->headers->delete($this->normalize($name));
    }


    public function get(string $name, $default = null, bool $onlyReturnFirst = true)
    {
        if ($this->has($name)) {
            $value = $this->headers->get($this->normalize($name));

            if ($onlyReturnFirst) {
                return $value[0];
            }

            return $onlyReturnFirst ? $value[0] : $value;
        }

        return $default;
    }


    public function getIterator() : ArrayIterator
    {
        return $this->headers->getIterator();
    }


    public function has(string $name) : bool
    {
        return $this->headers->has($this->normalize($name));
    }


    protected function normalize(string $name) : string
    {
        return strtr(strtolower($name), '_', '-');
    }


    public function set(string $name, $value, bool $replace = true) : void
    {
        $value = (array) $value;

        if (!$replace && $this->has($name)) {
            $value = array_merge((array) $this->get($name, []), $value);
        }

        $this->headers->set($this->normalize($name), $value);
    }


    public function toArray() : array
    {
        return $this->headers->toArray();
    }
}
