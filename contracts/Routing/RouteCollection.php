<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Routes Collection
 *
 */

namespace Contracts\Routing;

use Contracts\Support\Arrayable;
use Countable;
use IteratorAggregate; 

interface RouteCollection extends Arrayable, Countable, IteratorAggregate
{

    /**
     *  Collection Must Also Clone Route To Maintain Properties During Caching
     */
    public function __clone();


    /**
     *  Return Route By Name
     *
     *  @param string $name
     *  @return Route
     */
    public function get(string $name) : Route;


    /**
     *  @param string $name Route Name
     *  @return bool True If Exists, Otherwise False
     */
    public function has(string $name) : bool;


    /**
     *  Set New Route In Collection
     *
     *  @param string $name Route Name/Alias
     *  @param Route $route
     *  @return self
     */
    public function set(string $name, Route $route) : RouteCollection;
}
