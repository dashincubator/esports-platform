<?php declare(strict_types=1);

namespace System\QueryBuilder\Driver;

use Contracts\QueryBuilder\Driver as Contract;

class MysqlDriver extends AbstractDriver implements Contract
{

    protected function identifier(string $identifier) : string
    {
        return "`{$identifier}`";
    }
}
