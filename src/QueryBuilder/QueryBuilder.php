<?php declare(strict_types=1);

namespace System\QueryBuilder;

use Closure;
use Contracts\Container\Container;
use Contracts\Database\Connection;
use Contracts\QueryBuilder\{Driver, QueryBuilder as Contract};
use Contracts\QueryBuilder\Query\{Delete, Insert, Select, Update};
use Exception;
use System\QueryBuilder\Query\Components\{Placeholders, Table};

class QueryBuilder implements Contract
{

    private $connection;

    private $container;

    private $driver;

    private $placeholders;

    private $table;


    public function __construct(
        Connection $connection,
        Container $container,
        Driver $driver,
        Placeholders $placeholders,
        Table $table
    ) {
        $this->connection = $connection;
        $this->container = $container;
        $this->driver = $driver;
        $this->placeholders = $placeholders;
        $this->table = $table;
    }


    public function delete() : Delete
    {
        $query = $this->container->resolve(Delete::class, [$this->connection]);
        $query->table($this->table->getName(), $this->table->getAlias());

        return $query;
    }


    public function insert() : Insert
    {
        $query = $this->container->resolve(Insert::class, [$this->connection]);
        $query->table($this->table->getName(), $this->table->getAlias());

        return $query;
    }


    public function inTransaction() : bool
    {
        return $this->connection->inTransaction();
    }


    public function like(string $value) : string
    {
        return $this->driver->like($value);
    }


    public function placeholders(array $values) : string
    {
        return $this->placeholders->multiple($values);
    }


    public function select() : Select
    {
        $query = $this->container->resolve(Select::class, [$this->connection]);
        $query->table($this->table->getName(), $this->table->getAlias());

        return $query;
    }


    public function table(string $name, string $alias = '') : Contract
    {
        $this->table->set($name, $alias);

        return $this;
    }


    public function transaction(Closure $callback)
    {
        $rollback = false;

        $this->connection->beginTransaction();

        try {
            $result = $callback(function() use (&$rollback) {
                $rollback = true;
            });

            if ($rollback === true) {
                $this->connection->rollback();
            }
            else {
                $this->connection->commit();
            }
        }
        catch (Exception $e) {
            $this->connection->rollback();

            throw $e;
        }

        return $result;
    }


    public function update() : Update
    {
        $query = $this->container->resolve(Update::class, [$this->connection]);
        $query->table($this->table->getName(), $this->table->getAlias());

        return $query;
    }
}
