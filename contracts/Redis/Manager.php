<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Redis Client Collection
 *
 */

namespace Contracts\Redis;

interface Manager
{

    /**
     *  Retrieve Redis Client Associated With $name
     *
     *  @param string $name Name Of Redis Client
     *  @return mixed Redis Client
     *  @throws Exception If Client Does Not Exist
     */
    public function getClient(string $name = 'default');
}
