<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Database Driver
 *
 */

namespace Contracts\Database;

interface Driver
{

    /**
     *  Build Data Source Name
     *
     *  @param Server $server
     *  @param array $options Custom Options Determined By Adapter
     *  @return string Full DSN String For Adapter In Use
     */
    public function buildDsn(Server $server, array $options = []) : string;
}
