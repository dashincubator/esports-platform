<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class Fields extends Sql
{

    public function build(string ...$parts) : string
    {
        return implode(', ', array_unique(array_filter(array_map('trim', $parts))));
    }


    public function getSql(string $default = '') : string
    {
        if (!$this->parts) {
            return $default;
        }

        return $this->build(...$this->parts);
    }
}
