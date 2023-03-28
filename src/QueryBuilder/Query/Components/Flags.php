<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class Flags extends Sql
{

    public function getSql() : string
    {
        return implode(' ', array_map('strtoupper', array_unique($this->parts)));
    }
}
