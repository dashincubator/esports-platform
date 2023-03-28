<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Update SQL Query
 *
 */

namespace Contracts\QueryBuilder\Query;

interface Update extends Query
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
    public function flags(string ...$flags) : Update;


    /**
     *  @param int $limit Limit Of Rows To Update
     *  @return self
     */
    public function limit(int $limit) : Update;


    /**
     *  @param string $fields Order By Columns/Direction
     *  @return self
     */
    public function orderBy(string ...$fields) : Update;


    /**
     *  @param string $name Table Name
     *  @param string $alias Table Alias
     *  @return self
     */
    public function table(string $name, string $alias = '') : Update;


    /**
     *  @param array $values Values Being Updated ( Associative Array )
     *  @return self
     */
    public function values(array $values) : Update;


    /**
     *  Set Where Clause
     *
     *  @param string $where SQL Where Clause
     *  @param array $parameters Associated Parameters In Order Of Placeholders
     *  @return self
     */
    public function where(string $where, array $parameters = []) : Update;
}
