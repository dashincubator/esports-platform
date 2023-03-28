<?php

namespace App\DataSource;

use Exception;

abstract class AbstractRecord
{

    private $fields;

    private $modified = [];


    public function decrement(string $key, int $value = 1) : void
    {
        $this->set($key, $this->get($key) - abs($value));
    }


    public function get($key)
    {
        if (!in_array($key, $this->getFields())) {
            throw new Exception("'$key' Is Not A Valid Field For This Record " . get_called_class());
        }

        return $this->{$key};
    }


    // Manage Types From DB Storage
    // TODO: Replace With Typed Properties System
    protected function getCasts() : array
    {
        return [];
    }


    public function getFields() : array
    {
        if (is_null($this->fields)) {
            $fields = get_object_vars($this);

            // Delete AbstractRecord Properties
            foreach (array_keys(get_class_vars(self::class)) as $key) {
                unset($fields[$key]);
            }

            $this->fields = array_keys($fields);
        }

        return $this->fields;
    }


    public function getModified() : array
    {
        return $this->modified;
    }


    public function getPrimaryField() : string
    {
        throw new Exception("Primary Field Was Not Defined By This Record " . get_called_class());
    }


    public function getStorageValues() : array
    {
        $values = [];

        foreach ($this->getFields() as $field) {
            $value = $this->{$field};

            if (is_array($value)) {
                $value = json_encode($value);
            }
            elseif (is_bool($value)) {
                $value = $value ? 1 : 0;
            }
            elseif (is_string($value)) {
                $value = trim($value);
            }

            $values[$field] = $value;
        }

        return $values;
    }


    public function increment(string $key, int $value = 1) : void
    {
        $this->set($key, $this->get($key) + abs($value));
    }


    public function isEmpty() : bool
    {
        return count($this->modified) < 1;
    }


    public function set(string $key, $value) : void
    {
        if (!in_array($key, $this->getFields())) {
            throw new Exception("'{$key}' Is Not A Valid Field For This Record '" . get_called_class() . "'");
        }
        elseif (is_null($value)) {
            return;
        }

        $casts = $this->getCasts();

        if (array_key_exists($key, $casts)) {
            switch ($casts[$key]) {
                case 'array':
                    if (is_string($value)) {
                        $value = json_decode($value, true) ?? [];
                    }
                    elseif (!is_array($value)) {
                        throw new Exception("Field '{$key}' Expects JSON Or Array In Record '" . get_called_class() . "' - '" . gettype($value) . "' Given");
                    }
                    break;

                case 'bool':
                    $value = (bool) $value;
                    break;
            }
        }

        // Add To Modified Attributes List
        $this->modified[] = $key;

        // Set Value
        $this->{$key} = $value;
    }
}
