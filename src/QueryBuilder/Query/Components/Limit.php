<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class Limit
{

    private $limit;


    public function getSql() : string
    {
        return $this->limit ? "LIMIT {$this->limit}" : "";
    }


    public function reset() : void
    {
        $this->limit = null;
    }


    public function set(int $limit) : void
    {
        $this->limit = $limit;
    }
}
