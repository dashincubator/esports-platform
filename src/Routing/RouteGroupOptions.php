<?php declare(strict_types=1);

namespace System\Routing;

use Contracts\Routing\RouteGroupOptions as Contract;
use Contracts\Routing\RouteMiddleware;
use Closure;

class RouteGroupOptions implements Contract
{

    // subdomain.domain.tld
    private $host = '';

    // Apply To Route Middleware Closures To Route
    private $middleware = [];

    // Route Alias
    private $name = '';

    // Route/Group Prefix Pattern
    private $pattern = '';


    public function getHost() : string
    {
        return $this->host;
    }


    public function getMiddleware() : array
    {
        return $this->middleware;
    }


    public function getName() : string
    {
        return $this->name;
    }


    public function getPattern() : string
    {
        return $this->pattern;
    }


    public function host(string $host) : Contract
    {
        $this->host = $host;

        return $this;
    }


    public function middleware(Closure $c) : Contract
    {
        $this->middleware[] = $c;

        return $this;
    }


    public function name(string $name) : Contract
    {
        $this->name = $name;

        return $this;
    }


    public function pattern(string $pattern) : Contract
    {
        $this->pattern = $pattern;

        return $this;
    }
}
