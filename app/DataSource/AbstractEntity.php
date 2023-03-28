<?php

namespace App\DataSource;

use Contracts\Support\Arrayable;
use Exception;

abstract class AbstractEntity implements Arrayable
{

    private $passthrough = [
        'decrement',
        'getFields',
        'getModified',
        'getPrimaryField',
        'getStorageValues',
        'increment',
        'isEmpty'
    ];

    private $record;


    protected $fillable = [];

    protected $guarded = [];

    protected $increments = true;

    protected $hidden = [];


    public function __call(string $method, array $args)
    {
        if (in_array($method, $this->passthrough)) {
            return $this->record->{$method}(...$args);
        }

        if (mb_substr($method, 0, 3) === 'get' && $method !== 'get') {
            return $this->get(mb_substr($method, 3));
        }

        throw new Exception("'{$method}' Is Not A Valid Entity/Record Method");
    }


    public function __construct(AbstractRecord $record)
    {
        $this->record = $record;
    }


    public function deleted(int $rowCount) : void
    { }


    public function deleting() : void
    { }


    public function fill(array $values) : void
    {
        foreach ($values as $key => $value) {
            if (!$this->isFillable($key)) {
                throw new Exception("'{$key}' is not a valid 'fillable' property for " . get_called_class());
            }

            $this->set($key, $value);
        }
    }


    protected function get(string $key)
    {
        $method = 'get' . ucfirst($key);
        $value  = $this->record->get(lcfirst($key));

        if (method_exists($this, $method)) {
            $value = $this->{$method}($value);
        }

        return $value;
    }


    private function getFillable() : array
    {
        return $this->fillable;
    }


    private function getGuarded() : array
    {
        return $this->guarded;
    }


    private function getHidden() : array
    {
        return $this->hidden;
    }


    public function inserted(int $lastInsertId) : void
    {
        if (!$this->increments || $lastInsertId < 1) {
            return;
        }

        $this->set($this->record->getPrimaryField(), $lastInsertId);
    }


    public function inserting() : void
    { }


    private function isFillable(string $key) : bool
    {
        $fillable = $this->getFillable();
        $guarded = $this->getGuarded();

        // Guard Everything
        if (in_array('*', $guarded) || (!$fillable && !$guarded)) {
            return false;
        }

        if ($fillable) {
            if (!in_array($key, $fillable)) {
                return false;
            }
        }
        elseif ($guarded) {
            if (in_array($key, $guarded)) {
                return false;
            }
        }

        return true;
    }


    protected function push(string $key, $value) : void
    {
        if (is_null($value)) {
            return;
        }

        $data = $this->get($key) ?? [];
        $data[] = $value;

        $this->set($key, $data);
    }


    protected function set(string $key, $value) : void
    {
        if (is_null($value)) {
            return;
        }

        $method = 'set' . ucfirst($key);

        if (method_exists($this, $method)) {
            $value = $this->{$method}($value);
        }

        $this->record->set(lcfirst($key), $value);
    }


    public function toArray() : array
    {
        if ($this->record->isEmpty()) {
            return [];
        }

        $fields = $this->record->getFields();
        $hidden = $this->getHidden();
        $values = [];

        foreach ($fields as $field) {
            if (in_array($field, $hidden)) {
                continue;
            }

            $values[$field] = $this->get($field);
        }

        return $values;
    }


    public function updated(int $rowCount) : void
    { }


    public function updating() : void
    { }
}
