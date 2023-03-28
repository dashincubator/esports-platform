<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class Table
{

    // Table Alias
    private $alias;

    // Table Name
    private $name;


    public function getAlias() : string
    {
        return $this->alias ? $this->alias : '';
    }


    public function getName() : string
    {
        return $this->name ? $this->name : '';
    }


    public function hasAlias() : bool
    {
        return (bool) ($this->getAlias());
    }


    public function reset() : void
    {
        $this->alias = null;
        $this->name = null;
    }


    public function set(string $name, string $alias = '') : void
    {
        $this->alias = $alias;
        $this->name = $name;
    }
}
