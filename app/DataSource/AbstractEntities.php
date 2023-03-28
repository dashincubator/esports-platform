<?php

namespace App\DataSource;

use Contracts\Support\Arrayable;
use ArrayIterator;
use Closure;
use Countable;
use Exception;
use IteratorAggregate;

abstract class AbstractEntities implements Arrayable, Countable, IteratorAggregate
{

    protected $entities = [];


    public function __construct(AbstractEntity ...$entities)
    {
        foreach ($entities as $entity) {
            $this->entities[] = $entity;
        }
    }


    public function column(string $key) : array
    {
        $data = [];

        foreach ($this->entities as $entity) {
            $data[] = $entity->{'get' . ucfirst($key)}();
        }

        return $data;
    }


    public function combine(string $key) : array
    {
        $combined = [];

        foreach ($this->entities as $entity) {
            $combined[$entity->{'get' . ucfirst($key)}()] = $entity;
        }

        return $combined;
    }


    public function count() : int
    {
        return count($this->entities);
    }


    private function create(AbstractEntity ...$entities) : AbstractEntities
    {
        $class = get_called_class();

        return new $class(...$entities);
    }


    public function filter(Closure $fn) : AbstractEntities
    {
        $entities = [];

        foreach ($this->entities as $entity) {
            if (!$fn($entity)) {
                continue;
            }

            $entities[] = $entity;
        }

        return $this->create(...$entities);
    }


    public function get(string $key, $value, ?AbstractEntity $default = null) : AbstractEntity
    {
        $keys = [];

        foreach ($this->entities as $entity) {
            $keys[] = $entity->{'get' . ucfirst($key)}();

            if ($entity->{'get' . ucfirst($key)}() !== $value) {
                continue;
            }

            return $entity;
        }

        if (is_null($default)) {
            throw new Exception("{$key}:{$value} Entity Could Not Be Found In " . get_called_class());
        }

        return $default;
    }


    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->entities);
    }


    public function has(string $key, $value) : bool
    {
        foreach ($this->entities as $entity) {
            if ($entity->{'get' . ucfirst($key)}() !== $value) {
                continue;
            }

            return true;
        }

        return false;
    }


    public function isEmpty() : bool
    {
        return count($this->entities) === 0;
    }


    public function toArray() : array
    {
        $data = [];

        foreach ($this->entities as $key => $entity) {
            $data[] = $entity->toArray();
        }

        return $data;
    }
}
