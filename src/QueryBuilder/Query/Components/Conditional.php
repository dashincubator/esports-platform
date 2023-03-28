<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

use Exception;

class Conditional
{

    private $parameters;

    private $sql;

    // Clause Type ( Having/Where By )
    private $type;


    public function __construct(Parameters $parameters, Sql $sql, string $type)
    {
        $this->parameters = $parameters;
        $this->sql = $sql;
        $this->type = strtoupper($type);
    }


    public function add(string $sql, array $parameters) : void
    {
        $this->parameters->add($parameters);
        $this->sql->add($sql);
    }


    public function getParameters() : array
    {
        return $this->parameters->get();
    }


    public function getSql() : string
    {
        $sql = $this->sql->getSql();

        if (!$sql) {
            return '';
        }

        return "{$this->type} {$sql}";
    }


    public function reset() : void
    {
        $this->parameters->reset();
        $this->sql->reset();
    }
}
