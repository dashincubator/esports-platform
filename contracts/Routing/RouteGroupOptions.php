<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Route Group Options
 *
 */

namespace Contracts\Routing;

use Closure;

interface RouteGroupOptions
{

    /**
     *  Returns Group Host If Set, Otherwise Empty String
     *
     *  @return string
     */
    public function getHost() : string;


    /**
     *  @return array
     */
    public function getMiddleware() : array;


    /**
     *  Returns Group Name If Set, Otherwise Empty String
     *
     *  @return string
     */
    public function getName() : string;


    /**
     *  Returns Group Regex Pattern If Set, Otherwise Empty String
     *
     *  @return string
     */
    public function getPattern() : string;


    /**
     *  Set Group Host
     *
     *  @param string $host
     *  @return self
     */
    public function host(string $host) : RouteGroupOptions;


    /**
     *  Alter Middleware Collection
     *
     *  @param closure RouteMiddleware Is Passed Into Closure To Alter Collection
     *  @return self
     */
    public function middleware(Closure $c) : RouteGroupOptions;


    /**
     *  Set Group Options Name
     *
     *  @param string $name
     *  @return self
     */
    public function name(string $name) : RouteGroupOptions;


    /**
     *  Set Group Options Path/Pattern
     *
     *  @param string $pattern
     *  @return self
     */
    public function pattern(string $pattern) : RouteGroupOptions;
}
