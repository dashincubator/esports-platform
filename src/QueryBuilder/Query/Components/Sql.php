<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class Sql
{

    protected $parts = [];


    public function add(string ...$parts) : void
    {
        foreach ($parts as $part) {
            $this->parts[] = $part;
        }
    }


    public function build(string ...$parts) : string
    {
        return implode(' ', array_filter(array_map('trim', $parts)));
    }


    public function getSql() : string
    {
        return $this->build(...$this->parts);
    }


    public function reset() : void
    {
        $this->parts = [];
    }
}
