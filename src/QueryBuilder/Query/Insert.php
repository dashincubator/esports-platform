<?php declare(strict_types=1);

namespace System\QueryBuilder\Query;

use Contracts\Database\Connection;
use Contracts\QueryBuilder\Driver;
use Contracts\QueryBuilder\Query\Insert as Contract;
use Exception;
use System\QueryBuilder\Query\Components\{Fields, Flags, Placeholders, Sql, Table};

class Insert implements Contract
{

    // Dependencies
    private $connection;

    private $fields;

    private $flags;

    private $onDuplicateKeyUpdate = false;

    private $placeholders;

    private $sql;

    private $table;

    private $values = [];


    public function __construct(
        Connection $connection,
        Driver $driver,
        Fields $fields,
        Flags $flags,
        Placeholders $placeholders,
        Sql $sql,
        Table $table
    ) {
        $this->connection = $connection;
        $this->driver = $driver;
        $this->fields = $fields;
        $this->flags = $flags;
        $this->placeholders = $placeholders;
        $this->sql = $sql;
        $this->table = $table;
    }


    public function execute() : int
    {
        $statement = $this->connection->prepare($this->getSql());
        $statement->bindValues($this->getParameters());
        $statement->execute();

        return (int) $this->connection->lastInsertId();
    }


    public function flags(string ...$flags) : Contract
    {
        $this->flags->add(...$flags);

        return $this;
    }


    public function getParameters() : array
    {
        $parameters = [];

        foreach ($this->values as $row) {
            $parameters = array_merge($parameters, array_values($row));
        }

        return $parameters;
    }


    public function getSql(bool $escape = true) : string
    {
        if (!$this->values) {
            throw new Exception("Insert Query Is Missing Values For Query With Table: '{$this->table->getName()}'");
        }
        else {
            // Grab Array Keys From First Row Of Values
            $fields = array_keys($this->values[0]);
        }

        // Fill Array With Number Of Rows Being Inserted (Using Placeholders)
        $values = array_fill(0, count($this->values), $this->placeholders->multiple($fields));

        // Implode To Match Multi-Row Insert Format '(?, ?, ?), (?, ?, ?)'
        $values = implode(', ', $values);

        $sql = $this->sql->build(
            "INSERT",
            $this->flags->getSql(),
            "INTO {$this->table->getName()}",
            "({$this->fields->build(...$fields)})",
            "VALUES {$values}",
            $this->onDuplicateKeyUpdateFields(...$fields)
        );

        if ($escape) {
            $sql = $this->driver->escape($sql);
        }

        return $sql;
    }


    public function onDuplicateKeyUpdate() : Contract
    {
        $this->onDuplicateKeyUpdate = true;

        return $this;
    }


    private function onDuplicateKeyUpdateFields(string ...$fields) : string
    {
        if (!$this->onDuplicateKeyUpdate) {
            return '';
        }

        $update = [];

        foreach ($fields as $field) {
            $update[] = "{$field} = VALUES({$field})";
        }

        $update = implode(', ', $update);

        return "ON DUPLICATE KEY UPDATE {$update}";
    }


    public function table(string $name, string $alias = '') : Contract
    {
        $this->table->set($name, $alias);

        return $this;
    }


    public function values(array $values) : Contract
    {
        // Wraps Single Rows In An Array So We Can Batch Multi-Row Inserts
        if (!is_array(reset($values))) {
            $values = [$values];
        }

        foreach ($values as $key => $value) {
            ksort($value);

            $values[$key] = $value;
        }

        $this->values = array_merge($this->values, $values);

        return $this;
    }
}
