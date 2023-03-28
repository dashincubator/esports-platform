<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Insert SQL Query
 *
 */

namespace Contracts\QueryBuilder\Query;

interface Insert extends Query
{

    /**
     *  Executes SQL Created By Query Builder And Returns Last Insert Id
     *
     *  @return int
     */
    public function execute() : int;


    /**
     *  @param string $flags SQL Flags
     *  @return self
     */
    public function flags(string ...$flags) : Insert;


    /**
     *  @return self
     */
    public function onDuplicateKeyUpdate() : Insert;


    /**
     *  @param string $name Table Name
     *  @param string $alias Table Alias
     *  @return self
     */
    public function table(string $name, string $alias = '') : Insert;


    /**
     *  Accepts Both:
     *  - Single Row Inserts ['key' => 'value']
     *  - Multi-Row Inserts  [
     *      ['key' => 'value'],
     *      ['key' => 'value']
     *  ]
     *
     *  @param array $values
     *  @return self
     */
    public function values(array $values) : Insert;
}
