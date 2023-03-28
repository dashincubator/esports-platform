<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Database Server
 *
 */

namespace Contracts\Database;

interface Server
{

    /**
     *  Get Database Server Charset
     *
     *  @return string|null Charset If Set, Otherwise Null
     */
    public function getCharset() : string;


    /**
     *  Returns Database Server Host
     *
     *  @return string|null Host If Set, Otherwise Null
     */
    public function getHost() : string;


    /**
     *  Get Database Server Name
     *
     *  @return string|null Name If Set, Otherwise Null
     */
    public function getName() : string;


    /**
     *  Get Database Server Password
     *
     *  @return string|null Password If Set, Otherwise Null
     */
    public function getPassword() : string;


    /**
     *  Get Database Server Port
     *
     *  @return string|null Port If Set, Otherwise Null
     */
    public function getPort() : int;


    /**
     *  Get Database Socket Path
     *
     *  @return string
     */
    public function getSocket() : string;


    /**
     *  Get Database Server User
     *
     *  @return string|null User If Set, Otherwise Null
     */
    public function getUser() : string;
}
