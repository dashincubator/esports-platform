<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class GroupBy extends By
{

    public function __construct()
    {
        parent::__construct('group');
    }
}
