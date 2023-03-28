<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class By extends Sql
{

    // Clause Type ( Group/Order By )
    private $type;


    public function __construct(string $type)
    {
        $this->type = strtoupper($type);
    }


    public function getSql() : string
    {
        if (!$this->parts) {
            return '';
        }

        return "{$this->type} BY " . implode(', ', $this->parts);
    }
}
