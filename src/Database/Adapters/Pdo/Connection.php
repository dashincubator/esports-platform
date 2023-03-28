<?php declare(strict_types=1);

namespace System\Database\Adapters\Pdo;

use Contracts\Database\{Connection as Contract, Server};
use PDO;
use PDOStatement;

class Connection extends PDO implements Contract
{

    // Statement Cache
    private $cache = [];

    // Number Of Transactions We're Currently In
    private $transactions = 0;


    public function __construct(Server $server, string $dsn, array $options = [])
    {
        parent::__construct(
            $dsn,
            $server->getUser(),
            $server->getPassword(),
            $options
        );
        parent::setAttribute(PDO::ATTR_STATEMENT_CLASS, [Statement::class, [$this]]);
    }


    public function beginTransaction() : bool
    {
        $result = false;

        if (!$this->transactions++) {
            $result = parent::beginTransaction();
        }

        return $result;
    }


    public function commit() : bool
    {
        $result = false;

        if (!--$this->transactions) {
            $result = parent::commit();
        }

        return $result;
    }


    public function prepare($statement, $options = null) : PDOStatement
    {
        if (!array_key_exists($statement, $this->cache)) {
            $this->cache[$statement] = parent::prepare($statement, $options ?? []);
        }

        return $this->cache[$statement];
    }


    public function rollBack() : bool
    {
        $result = false;

        if ($this->transactions > 0) {
            $result = parent::rollBack();
        }

        $this->transactions = 0;

        return $result;
    }
}
