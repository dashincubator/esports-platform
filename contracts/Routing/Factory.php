<?php declare(strict_types=1);

namespace Contracts\Routing;

interface Factory
{

    /**
     *  Create New Route Instance With Properties
     *
     *  @see Route Comments
     */
    public function createRoute($methods, string $name, string $pattern, string $classname, string $classmethod = '') : Route;


    /**
     *  @return RouteGroupOptions
     */
    public function createRouteGroupOptions() : RouteGroupOptions;
}
