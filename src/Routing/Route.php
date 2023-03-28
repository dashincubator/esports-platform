<?php declare(strict_types=1);

namespace System\Routing;

use Contracts\Routing\Route as Contract;
use Contracts\Routing\RouteMiddleware;
use Closure;

class Route implements Contract
{

    // Method To Call On Class
    private $classmethod;

    // Class Name To Resolve For This Route
    private $classname;

    // subdomain.domain.tld
    private $host = '';

    // List Of HTTP Methods Assigned To This Route
    private $httpMethods = [];

    // Collection Of Middleware
    // - Could Be Class Names Or Closures, Decision Is Left To The Application
    private $middleware;

    // Route Alias
    private $name = '';

    // Parsed Pattern Data
    private $parsedData = [];

    // Route/Group Prefix Pattern
    private $pattern;

    // Variables Set On Matched Route
    private $variables = [];


    public function __clone()
    {
        $this->middleware = clone $this->middleware;
    }


    public function __construct(
        RouteMiddleware $middleware,
        $httpMethods,
        string $name,
        string $pattern,
        string $classname,
        string $classmethod = '',
        array $parsedData = []
    ) {
        $this->classmethod = $classmethod;
        $this->classname = $classname;
        $this->httpMethods = (array) $httpMethods;
        $this->middleware = $middleware;
        $this->name = $name;
        $this->parsedData = $parsedData;
        $this->pattern = $pattern;
    }


    public function getClassMethod() : string
    {
        return $this->classmethod;
    }


    public function getClassName() : string
    {
        return $this->classname;
    }


    public function getHost() : string
    {
        return $this->host;
    }


    public function getHttpMethods() : array
    {
        return $this->httpMethods;
    }


    public function getMiddleware() : RouteMiddleware
    {
        return $this->middleware;
    }


    public function getName() : string
    {
        return $this->name;
    }


    public function getParsedData() : array
    {
        return $this->parsedData;
    }


    public function getPattern() : string
    {
        return $this->pattern;
    }


    public function getVariables() : array
    {
        return $this->variables;
    }


    public function host(string $host) : Contract
    {
        $this->host = $host;

        return $this;
    }


    public function middleware(Closure $c) : Contract
    {
        $c($this->middleware);

        return $this;
    }


    public function variables(array $variables) : Contract
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }
}
