<?php

namespace App\Http\Middleware;

use App\View\Extensions\Route;
use Closure;
use Contracts\Container\Container;
use Contracts\Http\{Middleware as Contract, Request, Response};

class BindViewExtensionWithRoute implements Contract
{

    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $this->container->bind(Route::class, null, [$request->getAttributes()->get('route.name')]);

        return $next($request);
    }
}
