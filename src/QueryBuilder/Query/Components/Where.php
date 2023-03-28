<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class Where extends Conditional
{

    public function __construct(Parameters $parameters, Sql $sql )
    {
        parent::__construct($parameters, $sql, 'where');
    }
}
