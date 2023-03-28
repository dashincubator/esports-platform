<?php declare(strict_types=1);

namespace System\Collections;

use Contracts\Collections\Associative as Contract;
use Exception;
use ArrayIterator;

class Associative implements Contract
{

    protected $message = "'%s' Could Not Be Found In Associative Collection";

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


    public function delete(...$key) : void
    {
        foreach ($keys as $key) {
            $parts  = explode('.', $key);
            $values = &$this->values;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($values[$part]) and is_array($values[$part])) {
                    $values = &$values[$part];
                }
            }

            unset($values[array_shift($parts)]);
        }
    }


    public function get($key, $default = null)
    {
        $original = $key;

        return array_reduce(
            explode('.', $key),
            function ($values, $key) use ($default, $original) {
                if (array_key_exists($key, (array) $values)) {
                    return $values[$key];
                }

                if (is_null($default)) {
                    throw new Exception(sprintf($this->message, $original));
                }

                return $default;
            },
            $this->values
        );
    }


    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->values);
    }


    public function has($key) : bool
    {
        $values = $this->values;

        foreach (explode('.', $key) as $part) {
            if (!array_key_exists($part, (array) $values)) {
                return false;
            }

            $values = $values[$part];
        }

        return true;
    }


    public function intersect(array $keys) : array
    {
        $found = [];

        foreach ($keys as $key) {
            if (!$this->has($key)) {
                continue;
            }

            $found[$key] = $this->get($key);
        }

        return $found;
    }


    public function merge($key, $value) : void
    {
        $this->set($key, array_merge((array) $this->get($key, []), (array) $value));
    }


    public function prepend($key, $value) : void
    {
        $values = (array) $this->get($key, []);

        array_unshift($values, $value);

        $this->set($key, $values);
    }


    public function push($key, $value) : void
    {
        $values   = (array) $this->get($key, []);
        $values[] = $value;

        $this->set($key, $values);
    }


    public function replace(array $values) : void
    {
        $this->values = $values;
    }


    public function set($key, $value) : void
    {
        $values = &$this->values;

        foreach (explode('.', $key) as $k) {
            if (!array_key_exists($k, $values) || !is_array($values[$k])) {
                $values[$k] = [];
            }

            $values = &$values[$k];
        }

        $values = $value;
    }


    public function toArray() : array
    {
        return $this->values;
    }
}
