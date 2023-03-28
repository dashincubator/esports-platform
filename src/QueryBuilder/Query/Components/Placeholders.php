<?php declare(strict_types=1);

namespace System\QueryBuilder\Query\Components;

class Placeholders
{

    public function multiple(array $values) : string
    {
        // Fill Array With Placeholder Value '?'
        $placeholders = array_fill(0, count($values), '?');

        // Implode Placeholders To Match Array Placeholder Format '(?, ?, ?)'
        return "(" . implode(', ', $placeholders) . ")";
    }


    public function single() : string
    {
        return '?';
    }
}
