<?php declare(strict_types=1);

namespace System\QueryBuilder\Query;

use Contracts\Database\Connection;
use Contracts\QueryBuilder\Driver;
use Contracts\QueryBuilder\Query\Select as Contract;
use System\QueryBuilder\Query\Components\{Fields, Flags, GroupBy, Having, Joins, Limit, Offset, OrderBy, Sql, Table, Where};

class Select implements Contract
{

    // Query Alias
    private $alias;

    // Dependencies
    private $connection;

    private $driver;

    private $fields;

    private $flags;

    private $groupBy;

    private $having;

    private $joins;

    private $limit;

    private $offset;

    private $orderBy;

    private $sql;

    private $table;

    private $where;

    // For Update Flag
    private $forUpdate;


    public function __construct(
        Connection $connection,
        Driver $driver,
        Fields $fields,
        Flags $flags,
        GroupBy $groupBy,
        Having $having,
        Joins $joins,
        Limit $limit,
        Offset $offset,
        OrderBy $orderBy,
        Sql $sql,
        Table $table,
        Where $where
    ) {
        $this->connection = $connection;
        $this->driver = $driver;
        $this->fields = $fields;
        $this->flags = $flags;
        $this->groupBy = $groupBy;
        $this->having = $having;
        $this->joins = $joins;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderBy = $orderBy;
        $this->sql = $sql;
        $this->table = $table;
        $this->where = $where;
    }


    public function alias(string $alias) : Contract
    {
        $this->alias = $alias;

        return $this;
    }


    public function count() : int
    {
        $this->fields->reset();
        $this->fields->add('COUNT(*)');

        return $this->execute()[0]['COUNT(*)'] ?? 0;
    }


    public function execute() : array
    {
        $statement = $this->connection->prepare($this->getSql());
        $statement->bindValues($this->getParameters());
        $statement->execute();

        return $statement->fetchAll();
    }


    public function fields(string ...$fields) : Contract
    {
        $this->fields->add(...$fields);

        return $this;
    }


    public function first() : array
    {
        $this->limit(1);

        return $this->execute()[0] ?? [];
    }


    public function flags(string ...$flags) : Contract
    {
        $this->flags->add(...$flags);

        return $this;
    }


    public function forUpdate() : Contract
    {
        $this->forUpdate = true;

        return $this;
    }


    public function getParameters() : array
    {
        return array_merge(
            $this->where->getParameters(),
            $this->having->getParameters()
        );
    }


    public function getSql(bool $escape = true) : string
    {
        $sql = $this->sql->build(
            "SELECT",
            $this->flags->getSql(),
            $this->fields->getSql('*'),
            "FROM {$this->table->getName()}"
                . ($this->table->hasAlias() ? " AS {$this->table->getAlias()}" : ""),
            $this->joins->getSql(),
            $this->where->getSql(),
            $this->groupBy->getSql(),
            $this->having->getSql(),
            $this->orderBy->getSql(),
            $this->limit->getSql(),
            $this->offset->getSql(),
            $this->forUpdate ? "FOR UPDATE" : ""
        );

        if ($this->alias) {
            $sql = "({$sql}) AS {$this->alias}";
        }

        if ($escape) {
            $sql = $this->driver->escape($sql);
        }

        return $sql;
    }


    public function groupBy(string ...$fields) : Contract
    {
        $this->groupBy->add(...$fields);

        return $this;
    }


    public function having(string $having, array $parameters = []) : Contract
    {
        $this->having->add($having, $parameters);

        return $this;
    }


    public function join(string ...$joins) : Contract
    {
        $this->joins->add(...$joins);

        return $this;
    }


    public function limit(int $limit) : Contract
    {
        $this->limit->set($limit);

        return $this;
    }


    public function offset(int $offset) : Contract
    {
        $this->offset->set($offset);

        return $this;
    }


    public function orderBy(string ...$fields) : Contract
    {
        $this->orderBy->add(...$fields);

        return $this;
    }


    public function page(int $limit = 25, int $page = 1) : Contract
    {
        $page = $page - 1;

        if ($page < 0) {
            $page = 0;
        }

        $this->limit($limit);
        $this->offset($limit * $page);

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
