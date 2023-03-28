<?php declare(strict_types=1);

namespace System\Routing;

use Contracts\Container\Container;
use Contracts\Routing\{Factory as Contract, Route, RouteGroupOptions, RouteParser};

class Factory implements Contract
{

    private $container;

    // Global Middleware Added To All Routes
    private $middleware;

    private $parser;


    public function __construct(Container $container, RouteParser $parser, array $middleware = [])
    {
        $this->container = $container;
        $this->middleware = $middleware;
        $this->parser = $parser;
    }


    public function createRoute($methods, string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->container->resolve(Route::class, [
            $methods,
            $name,
            $pattern,
            $classname,
            $classmethod,
            $pattern === '' ? [] : $this->parser->parse($pattern)
        ])->middleware(function(RouteMiddleware $m) {
            $m->replace($this->middleware);
        });
    }


    public function createRouteGroupOptions() : RouteGroupOptions
    {
        return $this->container->resolve(RouteGroupOptions::class);
    }
}
