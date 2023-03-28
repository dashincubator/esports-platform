<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Database Connection
 *
 */

namespace Contracts\Database;

interface Connection
{

    /**
     *  Begins A Transaction
     *  Nested Transactions Are Allowed
     *
     *  @throws Exception Thrown By Adapter If There Was An Error Connecting To The Database
     */
    public function beginTransaction();


    /**
     *  Commits A Transaction
     *  If This Is the Final Nested Transaction Commit To Database
     *
     *  @throws Exception Thrown By Adapter If There Was An Error Connecting To The Database
     */
    public function commit();


    /**
     *  Returns The SQLSTATE Of The Last Query, If There Was One
     *
     *  @return string|null Error Code If Set, Otherwise null
     *  @throws Exception Thrown By Adapter If There Was An Error Connecting To The Database
     */
    public function errorCode();


    /**
     *  Returns Information About The Last Query Error
     *
     *  @return array Array Information About The Last Operation
     *  @throws Exception Thrown By Adapter If There Was An Error Connecting To The Database
     */
    public function errorInfo();


    /**
     *  Executes An SQL Statement In A Single Call
     *
     *  @param string $statement SQL Statement To Execute
     *  @return int Number Of Rows Affected By Statement
     *  @throws Exception Thrown By Adapter If There Was An Error Connecting To The Database
     */
    public function exec(string $statement);


    /**
     *  Returns Database Connection Attribute
     *
     *  @param int $attribute Connection Attribute Key
     *  @return mixed Value Of Connection Attribute
     */
    public function getAttribute($attribute);


    /**
     *  True If We Are In A Transaction, Otherwise False
     *
     *  @return bool
     *  @throws Exception Thrown By Adapter If There Was An Error Connecting To The Database
     */
    public function inTransaction();



    /**
     *  Returns Last Insert ID
     *
     *  @param string $name Name Of The Sequence Whose Id We Want
     *  @return string Id Of The Last Row In The Input Sequence
     */
    public function lastInsertId($name = null);


    /**
     *  Prepares An SQL Statement For Execution
     *
     *  @param string $statement The SQL Statement To Execute
     *  @param array $options Driver Options
     *  @return Statement The Statement
     *  @throws Exception Thrown By Adapter if there was an error connecting to the database
     */
    public function prepare(string $statement, array $options = []);


    /**
     *  Executes An SQL Statemenet And Gets The Statemenet Object
     *
     *  @param string $statement SQL Statement To Execute
     *  @return Statement The Statement
     *  @throws Exception Thrown By Adapter if there was an error connecting to the database
     */
    public function query();


    /**
     *  Quotes A String For A Query
     *
     *  @param string $string String To Quote
     *  @param int $type Const That Indicates Parameter Type
     *  @return string Quoted String
     *  @throws Exception Thrown By Adapter if there was an error connecting to the database
     */
    public function quote(string $string, int $type = 0);


    /**
     *  Rolls Back The Transaction
     *
     *  @throws Exception Thrown By Adapter if there was an error connecting to the database
     */
    public function rollBack();


    /**
     *  Set Database Connection Attribute
     *
     *  @param int $attribute Id Of Attribute
     *  @param mixed $value Value To Set
     *  @return bool True If Successful, Otherwise False
     *  @throws Exception Thrown By Adapter if there was an error connecting to the database
     */
    public function setAttribute(int $attribute, $value);
}
