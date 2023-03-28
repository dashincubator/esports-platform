<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Route Compiler
 *
 */

namespace Contracts\Routing;

interface RouteCompiler
{

    /**
     *  Compiles Route Collection Into Data Used By Route Matcher
     *
     *  @param RouteCollection $collection
     *  @return array ['dynamic', 'static'] Sequential Array
     */
    public function compile(RouteCollection $collection) : array;
}
