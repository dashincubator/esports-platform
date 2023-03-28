<?php

namespace App\Bootstrap\Stages\Http;

use Contracts\Http\{Request, Response};
use Contracts\Routing\Matcher;
use Closure;

class MatchRoute
{

    private const DEFAULT_METHOD = 'handle';

    private const FALLBACK_ROUTE = 'fallback';

    private const MIDDLEWARE_METHOD = 'handle';


    private $matcher;


    public function __construct(Matcher $matcher)
    {
        $this->matcher = $matcher;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $route = $this->matcher->match(self::FALLBACK_ROUTE, '', $request->getMethod(), $request->getPath());

        $request->getAttributes()->set('route', [
            'class' => [
                'name' => $route->getClassName(),
                'method' => ($route->getClassMethod() ? $route->getClassMethod() : self::DEFAULT_METHOD)
            ],
            'isFallback' => ($route->getName() === self::FALLBACK_ROUTE),
            'middleware' => [
                'classnames' => $route->getMiddleware()->toArray(),
                'method' => self::MIDDLEWARE_METHOD
            ],
            'name' => $route->getName(),
            'variables' => $route->getVariables()
        ]);

        return $next($request);
    }
}
