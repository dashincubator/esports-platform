<?php declare(strict_types=1);

namespace System\View\Extensions;

use Closure;
use Contracts\Support\Arrayable;
use Contracts\View\Extensions\Data as Contract;
use Exception;

class Data implements Contract
{

    private const DEFAULT_VALUE = '';

    private const VALID_PROTOCOLS = [
        'ftp',
        'http',
        'https'
    ];


    private $values = [];


    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
    }


    public function count() : int
    {
        return count($this->values);
    }


    public function current()
    {
        return $this->escape('', current($this->values));
    }


    private function escape($firstCharacter, $value)
    {
        // Make Sure All Data Is Escaped
        switch(gettype($value)) {
            case 'boolean':
            case 'integer':
            case 'double':
            case 'NULL':
                return $value;

            case 'array':
                return new self($value);

            case 'object':
                if ($value instanceof Data) {
                    return $value;
                }
                elseif ($value instanceof Arrayable) {
                    return new self($value->toArray());
                }
                break;

            case 'string':
                // Escape Value If '!' Was Not Provided With Key
                if ($firstCharacter !== '!') {
                    if ($firstCharacter === ':') {
                        return $this->escapeUrl($value);
                    }

                    return $this->escapeStr($value);
                }

                return $value;
        }

        throw new Exception("View Data Received Invalid Value To Escape");
    }


    private function escapeStr($str) : string
    {
        $str = htmlspecialchars_decode($str, ENT_QUOTES | ENT_SUBSTITUTE);
        $str = htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return $str;
    }


    private function escapeUrl($str): string
    {
        do {
            $before = $str;
            $colon_position = mb_strpos($str, ':');

            // A Colon Was Found, Validate Protocol
            if ($colon_position > 0) {
                $protocol = mb_substr($str, 0, $colon_position);

                // If A Colon Is Preceded By '/', '?' Or '#' This Must Be A Relative URL.
                if (preg_match('![/?#]!', $protocol)) {
                    break;
                }

                // Validate Protocol Per RFC2616 Standard (Case-Insensitive Protocol)
                if (!isset(self::VALID_PROTOCOLS[strtolower($protocol)])) {
                    $str = mb_substr($str, $colon_position + 1);
                }
            }
        } while ($before !== $str);

        return $this->escapeStr($str);
    }


    private function has($key) : bool
    {
        return $this->offsetGet($key) !== self::DEFAULT_VALUE;
    }


    public function key()
    {
        return $this->escape('', key($this->values));
    }


    public function next()
    {
        return $this->escape('', next($this->values));
    }


    public function offsetExists($key) : bool
    {
        return $this->has($key);
    }


    public function offsetGet($key)
    {
        $firstCharacter = $key[0] ?? '';

        if (in_array($firstCharacter, ['!', ':'])) {
            $key = mb_substr($key, 1);
        }

        $value = array_reduce(
            $this->parseKey($key),
            function ($values, $part) {
                if ($values instanceof Data) {
                    return $values[$part];
                }
                elseif ($values instanceof Arrayable) {
                    $values = $values->toArray();
                }

                if (!is_array($values) || !array_key_exists($part, $values)) {
                    return self::DEFAULT_VALUE;
                }

                return $values[$part];
            },
            $this->values
        );

        return $this->escape($firstCharacter, $value);
    }


    public function offsetSet($key, $value) : void
    { }


    public function offsetUnset($key) : void
    { }


    private function parseKey($key) : array
    {
        return (array) (is_string($key) ? explode('.', $key) : $key);
    }


    public function rewind() : void
    {
        reset($this->values);
    }


    private function set($key, $value) : void
    {
        $values = &$this->values;

        foreach ($this->parseKey($key) as $k) {
            if (!isset($values[$k]) || !is_array($values[$k])) {
                $values[$k] = [];
            }

            $values = &$values[$k];
        }

        $values = $value;
    }


    public function toArray() : array
    {
        return $this->toArrayLoop($this);
    }


    private function toArrayLoop($input)
    {
        if (is_object($input)) {
            $output = [];

            foreach ($input as $key => $value) {
                $output[$key] = $this->toArrayLoop($value);
            }

            return $output;
        }

        return $input;
    }


    public function valid() : bool
    {
        return !is_null(key($this->values));
    }
}
