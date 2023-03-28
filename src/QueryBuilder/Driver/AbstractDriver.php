<?php declare(strict_types=1);

namespace System\QueryBuilder\Driver;

abstract class AbstractDriver
{

    protected $escape;

    protected $identifiers;

    protected $spacers = [
        '(' => '( ',
        ')' => ' )',
        '.' => ' . ',
        ',' => ' , '
    ];


    public function __construct(bool $escape = false, array $identifiers = [])
    {
        $this->escape = $escape;
        $this->identifiers = $escape ? $this->buildIdentifierMap($identifiers) : [];
    }


    // Attempting To Match Whole Word Without Regex
    // - Currently Doesn't Support/Match SQL Aliases That Do Not Use 'AS'
    private function buildAliasMap(string $sql) : array
    {
        $parts = explode(' AS ', str_replace(' as ', ' AS ', $sql));
        $replace = [];

        unset($parts[0]);

        foreach ($parts as $part) {
            $alias = trim(explode(' ', $part, 2)[0], ',');

            $replace[" {$alias} "] = " {$this->identifier($alias)} ";
        }

        return $replace;
    }


    private function buildIdentifierMap(array $identifiers) : array
    {
        $replace = [];

        foreach ($identifiers as $identifier) {
            $replace[" {$identifier} "] = " {$this->identifier($identifier)} ";
        }

        return $replace;
    }


    public function escape(string $sql) : string
    {
        if (!$this->escape) {
            return $sql;
        }

        $replace = array_merge($this->spacers, $this->identifiers, $this->buildAliasMap($sql), array_flip($this->spacers));

        // Adding Trailing Space To Catch Identifiers At The End Of The String
        return trim(str_replace(array_keys($replace), array_values($replace), $sql . ' '));
    }


    public function like(string $like) : string
    {
        // Backslash Escapes Wildcards
        $like = str_replace('\\', '\\\\', $like);

        // Standard Wildcards Use Underscore And Percent Sign.
        return str_replace(['%', '_'], ['\\%', '\\_'], $like);
    }
}
