<?php

namespace App;

use Contracts\Collections\Associative as Collection;
use Contracts\Support\Arrayable;

class Flash implements Arrayable
{

    private const ERROR = 'error';

    private const INFO = 'info';

    private const INPUT = 'input';

    private const SUCCESS = 'success';

    private const WARNING = 'warning';


    private $collection;


    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }


    public function error($values) : void
    {
        $this->push(self::ERROR, $values);
    }


    private function get(string $key) : array
    {
        return array_unique(array_filter($this->collection->get($key, [])));
    }


    public function info($values) : void
    {
        $this->push(self::INFO, $values);
    }


    public function input($values) : void
    {
        $this->collection->set(self::INPUT, $values);
    }


    private function push(string $key, $values) : void
    {
        foreach ((array) $values as $value) {
            $this->collection->push($key, (string) $value);
        }
    }


    public function success($values) : void
    {
        $this->push(self::SUCCESS, $values);
    }


    public function toArray() : array
    {
        $data = [];

        foreach ([self::ERROR, self::INFO, self::INPUT, self::SUCCESS, self::WARNING] as $key) {
            $data[$key] = $this->get($key);
        }

        $this->collection->replace([]);

        return $data;
    }


    public function warning($values) : void
    {
        $this->push(self::WARNING, $values);
    }
}
