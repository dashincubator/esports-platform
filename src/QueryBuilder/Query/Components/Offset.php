<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class Offset
{

    private $offset;


    public function getSql() : string
    {
        return $this->offset ? "OFFSET {$this->offset}" : "";
    }


    public function reset() : void
    {
        $this->offset = null;
    }


    public function set(int $offset) : void
    {
        $this->offset = $offset;
    }
}
