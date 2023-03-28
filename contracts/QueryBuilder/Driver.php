<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  QueryBuilder Database Driver
 *
 */

namespace Contracts\QueryBuilder;

interface Driver
{

    /**
     *  Escape SQL Identifiers
     *
     *  @param string $sql
     *  @return string
     */
    public function escape(string $sql) : string;


    /**
     *  Escape Like SQL Strings
     *
     *  @param string $like
     *  @return string
     */
    public function like(string $like) : string;
}
