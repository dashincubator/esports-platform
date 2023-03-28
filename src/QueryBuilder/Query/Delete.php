<?php declare(strict_types=1);

namespace System\QueryBuilder\Query;

use Contracts\Database\Connection;
use Contracts\QueryBuilder\Driver;
use Contracts\QueryBuilder\Query\Delete as Contract;
use System\QueryBuilder\Query\Components\{Flags, Limit, OrderBy, Sql, Table, Where};

class Delete implements Contract
{

    // Dependencies
    private $connection;

    private $flags;

    private $limit;

    private $orderBy;

    private $sql;

    private $table;

    private $where;


    public function __construct(
        Connection $connection,
        Driver $driver,
        Flags $flags,
        Limit $limit,
        OrderBy $orderBy,
        Sql $sql,
        Table $table,
        Where $where
    ) {
        $this->connection = $connection;
        $this->driver = $driver;
        $this->flags = $flags;
        $this->limit = $limit;
        $this->orderBy = $orderBy;
        $this->sql = $sql;
        $this->table = $table;
        $this->where = $where;
    }


    public function execute() : int
    {
        $statement = $this->connection->prepare($this->getSql());
        $statement->bindValues($this->getParameters());
        $statement->execute();

        return $statement->rowCount();
    }


    public function flags(string ...$flags) : Contract
    {
        $this->flags->add(...$flags);

        return $this;
    }


    public function getParameters() : array
    {
        return $this->where->getParameters();
    }


    public function getSql(bool $escape = true) : string
    {
        $sql = $this->sql->build(
            "DELETE",
            $this->flags->getSql(),
            "FROM {$this->table->getName()}",
            $this->where->getSql(),
            $this->orderBy->getSql(),
            $this->limit->getSql()
        );

        if ($escape) {
            $sql = $this->driver->escape($sql);
        }

        return $sql;
    }


    public function limit(int $limit) : Contract
    {
        $this->limit->set($limit);

        return $this;
    }


    public function orderBy(string ...$fields) : Contract
    {
        $this->orderBy->add(...$fields);

        return $this;
    }


    public function table(string $name, string $alias = '') : Contract
    {
        $this->table->set($name, $alias);

        return $this;
    }


    public function where(string $where, array $parameters = []) : Contract
    {
        $this->where->add($where, $parameters);

        return $this;
    }
}
