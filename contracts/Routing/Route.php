<?php declare(strict_types=1);

namespace Contracts\Routing;

use Contracts\Collections\Sequential;
use Closure;

interface Route
{

    /**
     *  Returns Class Method Assigned To Route
     *
     *  @return string
     */
    public function getClassMethod() : string;


    /**
     *  Returns Class Name Assigned To Route
     *
     *  @return string
     */
    public function getClassName() : string;


    /**
     *  Returns Host If Set, Otherwise Empty String
     *
     *  @return string
     */
    public function getHost() : string;


    /**
     *  Returns HTTP Methods Assigned To Route
     *
     *  @return array
     */
    public function getHttpMethods() : array;


    /**
     *  @return RouteMiddleware
     */
    public function getMiddleware() : RouteMiddleware;


    /**
     *  Returns Name If Set, Otherwise Empty String
     *
     *  @return string
     */
    public function getName() : ?string;


    /**
     *  Returns Parsed Route Data
     *
     *  @return array
     */
    public function getParsedData() : array;


    /**
     *  Returns Regex Pattern Or Static Path If Set, Otherwise Null
     *
     *  @return string
     */
    public function getPattern() : string;


    /**
     *  Returns Route Variables If Exists, Otherwise Empty Array
     *
     *  @return array
     */
    public function getVariables() : array;


    /**
     *  Set Host
     *
     *  @param string $host
     *  @return self
     */
    public function host(string $host) : Route;


    /**
     *  Alter Middleware Collection
     *
     *  @param closure RouteMiddleware Is Passed Into Closure To Alter Collection
     *  @return self
     */
    public function middleware(Closure $m) : Route;


    /**
     *  Sets Route Variables On Successful Match
     *
     *  @param array $variables
     *  @return self
     */
    public function variables(array $variables) : Route;
}
