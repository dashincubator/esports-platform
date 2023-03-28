<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Database Adapter Statement
 *
 */

namespace Contracts\Database;

interface Statement
{

    /**
     *  Binds A Parameter To The Specified Variable Name
     *
     *  @param mixed $parameter Named Placeholder, eg ":id", Or 1-Indexed Position Of The Parameter
     *  @param mixed $variable Value Of The Parameter
     *  @param int|null $type Adapter Type Indicating The Type Of Data We're Binding
     *  @param int|null $length Length Of Data Type
     *  @param array $options Adapter Options
     *  @return bool True If Successful, Otherwise False
     */
    public function bindParam($parameter, &$variable, $type = null, $length = null, $options = null);


    /**
     *  Binds Value To The Statement
     *
     *  @param mixed $parameter Named Placeholder, eg ":id", Or 1-Indexed Position Of The Parameter
     *  @param mixed $value Value Of The Parameter
     *  @param int $type Adapter Type Indicating The Type Of Data We're Binding
     *  @return bool True If Successful, Otherwise False
     */
    public function bindValue($parameter, $value, $type = null);


    /**
     *  Bind Multiple Values To The Statement
     *
     *  @param array $values The Mapping Of Parameter Name To A Value Or To An Array.
     *  If Mapping To An Array, The First Item Should Be The Value And The Second Should
     *  Be The Data Type Constant.
     *  @return bool True If Successful, Otherwise False
     */
    public function bindValues($values);


    /**
     *  Frees Up The Connection To The Server So That Other SQL Statements May Be Issued,
     *  But Leaves The Statement In A State That Enables It To Be Executed Again.
     *
     *  @return bool True If Successful, Otherwise False
     */
    public function closeCursor();


    /**
     *  Returns The Number Of Columns In The Result Set
     *
     *  @return int
     */
    public function columnCount();


    /**
     *  Returns The SQLSTATE Associated With The Last Operation
     *
     *  @return string
     */
    public function errorCode();


    /**
     *  Returns Information About The Error That Occured In The Last Operation
     *
     *  @return array
     */
    public function errorInfo();


    /**
     *  Execute The Prepared Statement
     *
     *  @param array|null $parameters The List Of Parameters To Bind To The Statement
     *  @return bool True If Successful, Otherwise False
     */
    public function execute($parameters = null);


    /**
     *  Fetches Next Row From A Result Set
     *
     *  @param int|null $mode Adapter Constant That Specifies How The Next Row Will Be Returned
     *  @return array|bool The Row If Successful, Otherwise False
     */
    public function fetch($mode = null, $orientation = null, $offset = null);


    /**
     *  Fetches All The Result Rows
     *
     *  @param int|null $mode Adapter Constant That Specifies How The Next Row Will Be Returned
     *  @return array|bool The Row If Successful, Otherwise False
     */
    public function fetchAll($mode = null, $classname = null, $args = null);


    /**
     *  Return A Single Column From The Next Row Of A Result Set
     *
     *  @param int $number The 0-Indexed Number Of The Column You Wish To Retrieve From The Row
     *  @return mixed The Data From The Specified Column
     */
    public function fetchColumn($number = 0);


    /**
     *  Returns Type Of Value For Adapter
     *
     *  @param mixed $value Value To Get Type For
     *  @return int
     */
    public function getType($value);


    /**
     *  Returns Number Of Rows Affected By Last Operation
     *
     *  @return int
     */
    public function rowCount();


    /**
     *  Sets The Fetch Mode To Be Used For All Fetch* Methods
     *
     *  @param int $fetchMode Adapter Constant That Specifies How The Next Row Will Be Returned
     *  @return bool True If Successful, Otherwise False
     */
    public function setFetchMode($mode, $params = null);
}
