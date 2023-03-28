<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Select SQL Query
 *
 */

namespace Contracts\QueryBuilder\Query;

interface Select extends Query
{

    /**
     *  @param string $alias Query Alias
     */
    public function alias(string $alias) : Select;


    /**
     *  Execute Query And Retrieve Count
     *
     *  @return int
     */
    public function count() : int;


    /**
     *  Executes SQL Created By Query Builder And Returns Data Found
     *
     *  @return array
     */
    public function execute() : array;


    /**
     *  @param string $fields
     *  @return self
     */
    public function fields(string ...$fields) : Select;


    /**
     *  Set Limit To 1 -> Execute Query Retrieving Only First Value
     *
     *  @return array
     */
    public function first() : array;


    /**
     *  @param string $flags SQL Flags
     *  @return self
     */
    public function flags(string ...$flags) : Select;


    /**
     *  @return self
     */
    public function forUpdate() : Select;


    /**
     *  @param string $fields
     *  @return self
     */
    public function groupBy(string ...$fields) : Select;


    /**
     *  Set Having Clause
     *
     *  @param string $where SQL Having Clause
     *  @param array $parameters Associated Parameters In Order Of Placeholders
     *  @return self
     */
    public function having(string $having, array $parameters = []) : Select;


    /**
     *  Set Join Clause(s)
     *
     *  @param string $joins SQL Join Clauses
     *  @return self
     */
    public function join(string ...$joins) : Select;


    /**
     *  @param int $limit
     *  @return self
     */
    public function limit(int $limit) : Select;


    /**
     *  @param int $offset
     *  @return self
     */
    public function offset(int $offset) : Select;


    /**
     *  @param string $fields Order By Columns/Direction
     *  @return self
     */
    public function orderBy(string ...$fields) : Select;


    /**
     *  @param int $limit
     *  @param int $page Page Being Viewed
     *  @return self
     */
    public function page(int $limit = 25, int $page = 1) : Select;


    /**
     *  @param string $name Table Name
     *  @param string $alias Table Alias
     *  @return self
     */
    public function table(string $name, string $alias = '') : Select;


    /**
     *  Set Where Clause
     *
     *  @param string $where SQL Where Clause
     *  @param array $parameters Associated Parameters In Order Of Placeholders
     *  @return self
     */
    public function where(string $where, array $parameters = []) : Select;
}
