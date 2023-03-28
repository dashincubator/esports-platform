<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  QueryBuilder Factory
 *
 */

namespace Contracts\QueryBuilder;

use Closure;
use Contracts\QueryBuilder\Query\{Delete, Insert, Select, Update};

interface QueryBuilder
{

    /**
     *  @return Delete
     */
    public function delete() : Delete;


    /**
     *  @return Insert
     */
    public function insert() : Insert;


    /**
     *  @return bool True If Connection Used By QueryBuilder Is In Transaction, Otherwise False
     */
    public function inTransaction() : bool;


    /**
     *  Use Database Driver To Escape Like String
     *
     *  @param string $value
     *  @return string
     */
    public function like(string $value) : string;


    /**
     *  Build Placeholders For In SQL ( @see return value )
     *
     *  @param array $values
     *  @return string Something Like (?, ?, ?)
     */
    public function placeholders(array $values) : string;


    /**
     *  @return Select
     */
    public function select() : Select;


    /**
     *  Table Name/Alias To Set On All Queries Created By This QueryBuilder
     *
     *  @param string $name Table Name
     *  @param string $alias Table Alias
     *  @return self
     */
    public function table(string $name, string $alias = '') : QueryBuilder;


    /**
     *  Execute Queries In '$callback' Within A Transaction; '$callback' Receives
     *  A Closure That Can Rollback Transaction On Error;
     *
     *  If An Exception Is Thrown Within '$callback', DB, etc. Catch, Rollback,
     *  And Finally Throw Exception;
     *
     *  @param Closure $callback
     *  @return mixed
     */
    public function transaction(Closure $callback);


    /**
     *  @return Update
     */
    public function update() : Update;
}
