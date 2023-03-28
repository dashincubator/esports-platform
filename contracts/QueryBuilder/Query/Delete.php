<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Delete SQL Query
 *
 */

namespace Contracts\QueryBuilder\Query;

interface Delete extends Query
{

    /**
     *  Executes SQL Created By Query Builder And Returns Rows Affected
     *
     *  @return int
     */
    public function execute() : int;


    /**
     *  @param string $flags SQL Flags
     *  @return self
     */
    public function flags(string ...$flags) : Delete;


    /**
     *  @param int $limit Limit Of Rows To Delete
     *  @return self
     */
    public function limit(int $limit) : Delete;


    /**
     *  @param string $fields Order By Columns/Direction
     *  @return self
     */
    public function orderBy(string ...$fields) : Delete;


    /**
     *  @param string $name Table Name
     *  @param string $alias Table Alias
     *  @return self
     */
    public function table(string $name, string $alias = '') : Delete;


    /**
     *  Set Where Clause
     *
     *  @param string $where SQL Where Clause
     *  @param array $parameters Associated Parameters In Order Of Placeholders
     *  @return self
     */
    public function where(string $where, array $parameters = []) : Delete;
}
