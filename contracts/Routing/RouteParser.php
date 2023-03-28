<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Route Parser
 *
 *  Parses Route Strings Of The Following Form:
 *  "/user/{name}[/{id:[0-9]+}]"
 *
 */

namespace Contracts\Routing;

interface RouteParser
{

    /**
     *  @param string $pattern Route Path/Regex String
     *  @return array Parsed Route Data
     */
    public function parse(string $pattern) : array;
}
