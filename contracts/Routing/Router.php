<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Router/Route Builder
 *
 */

namespace Contracts\Routing;

use Closure;

interface Router
{

    /**
     *  Create A Route Matching The HTTP Method
     *
     *  When Creating Routes Group Options Are Applied, The Route Is Added To The
     *  Routes Collection, And The Route Is Returned.
     *
     *  @see Route Parameter Comments
     *  @return Route
     */
    public function any(string $name, string $pattern, string $classname, string $classmethod = '') : Route;
    public function delete(string $name, string $pattern, string $classname, string $classmethod = '') : Route;
    public function get(string $name, string $pattern, string $classname, string $classmethod = '') : Route;
    public function head(string $name, string $pattern, string $classname, string $classmethod = '') : Route;
    public function options(string $name, string $pattern, string $classname, string $classmethod = '') : Route;
    public function patch(string $name, string $pattern, string $classname, string $classmethod = '') : Route;
    public function post(string $name, string $pattern, string $classname, string $classmethod = '') : Route;
    public function put(string $name, string $pattern, string $classname, string $classmethod = '') : Route;


    /**
     *  Create Group Options Instance With Parameter Applied
     *
     *  @return RouteGroupOptions       [description]
     */
    public function host(string $host) : RouteGroupOptions;
    public function middleware(Closure $c) : RouteGroupOptions;
    public function name(string $name) : RouteGroupOptions;
    public function pattern(string $pattern) : RouteGroupOptions;


    /**
     *  @return Routes
     */
    public function getRoutes() : RouteCollection;

    /**
     *  Apply Group Options To Routes Within Closure
     *
     *  @param RouteGroupOptions $options
     *  @param Closure $routes
     */
    public function group(RouteGroupOptions $options, Closure $routes) : void;


    /**
     *  Create A Route Matching HTTP Method(s) Provided By $methods
     *
     *  @see Comment Block Above
     */
    public function match($methods, string $name, string $pattern, string $classname, string $classmethod = '') : Route;


    /**
     *  Find Route By Name, And Build Uri With Parameters Provided
     *
     *  @param string $name Name Of Route
     *  @param array $params
     *  @return string
     *  @throws Exception Thrown When URI Cannot Be Created
     */
    public function uri(string $name, array $params = []) : string;

    /**
     *   Add Domain To URI
     *
     *   @see $this->uri Commends
     */
    public function url(string $name, array $params = []) : string;
}
