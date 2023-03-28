<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Fork Of:
 *  - Opulence Version 1.1 By David Young - https://www.opulencephp.com
 *  - FastRoute Version 2.0 By Nikic - https://github.com/nikic/FastRoute
 *
 */

namespace System\Routing;

use Contracts\Routing\{Factory, RouteGroupOptions, Route, Router as Contract, RouteCollection};
use Closure;
use Exception;

class Router implements Contract
{

    private const HTTP_METHODS = [
        'delete' => 'DELETE',
        'get' => 'GET',
        'head' => 'HEAD',
        'options' => 'OPTIONS',
        'patch' => 'PATCH',
        'post' => 'POST',
        'put' => 'PUT'
    ];


    private $collection;

    private $debug;

    private $domain;

    private $factory;

    private $groupOptionsStack = [];


    public function __construct(Factory $factory, RouteCollection $collection, bool $debug = false, string $domain = '')
    {
        $this->collection = $collection;
        $this->debug = $debug;
        $this->domain = rtrim($domain, '/');
        $this->factory = $factory;
    }


    public function any(string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->match(array_values(self::HTTP_METHODS), $name, $pattern, $classname, $classmethod);
    }


    private function create($methods, string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        $host = '';
        $middleware = [];
        $names = '';
        $patterns = '';

        foreach ($this->groupOptionsStack as $options) {
            $host = $options->getHost() . $host;
            $names .= $options->getName();
            $patterns .= $options->getPattern();

            $middleware = array_merge($middleware, $options->getMiddleware());
        }

        $name = ($names . $name);
        $route = $this->factory->createRoute(
            $methods,
            $name,
            ($patterns . $pattern),
            $classname,
            $classmethod
        )
        ->host($host);

        foreach ($middleware as $closure) {
            $route->middleware($closure);
        }

        $this->collection->set($name, $route);

        return $this->collection->get($name);
    }


    public function delete(string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->create(self::HTTP_METHODS['delete'], $name, $pattern, $classname, $classmethod);
    }


    public function get(string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->create(self::HTTP_METHODS['get'], $name, $pattern, $classname, $classmethod);
    }


    public function getRoutes() : RouteCollection
    {
        return $this->collection;
    }


    public function group(RouteGroupOptions $options, Closure $routes) : void
    {
        $this->groupOptionsStack[] = $options;
        $routes($this);
        array_pop($this->groupOptionsStack);
    }


    public function head(string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->create(self::HTTP_METHODS['head'], $name, $pattern, $classname, $classmethod);
    }


    public function host(string $host) : RouteGroupOptions
    {
        return $this->factory->createRouteGroupOptions()->host($host);
    }


    public function match($methods, string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->create($methods, $name, $pattern, $classname, $classmethod);
    }


    public function middleware(Closure $c) : RouteGroupOptions
    {
        return $this->factory->createRouteGroupOptions()->middleware($c);
    }


    public function name(string $name) : RouteGroupOptions
    {
        return $this->factory->createRouteGroupOptions()->name($name);
    }


    public function options(string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->create(self::HTTP_METHODS['options'], $name, $pattern, $classname, $classmethod);
    }


    public function patch(string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->create(self::HTTP_METHODS['patch'], $name, $pattern, $classname, $classmethod);
    }


    public function pattern(string $pattern) : RouteGroupOptions
    {
        return $this->factory->createRouteGroupOptions()->pattern($pattern);
    }


    public function post(string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->create(self::HTTP_METHODS['post'], $name, $pattern, $classname, $classmethod);
    }


    public function put(string $name, string $pattern, string $classname, string $classmethod = '') : Route
    {
        return $this->create(self::HTTP_METHODS['put'], $name, $pattern, $classname, $classmethod);
    }


    public function has(string $name) : bool
    {
        return $this->collection->has($name);
    }


    // Fork Of:
    // - https://github.com/northwoods/router
    // - https://github.com/nikic/FastRoute/issues/66
    public function uri(string $name, array $params = []) : string
    {
        $missing = [];
        $paths = $this->collection->get($name)->getParsedData();

        foreach (array_reverse($paths) as $parts) {
            $missing = array_column(array_filter($parts, 'is_array'), 0);
            $missing = array_diff($missing, array_keys($params));

            // Try Next Route
            if (count($missing) > 0) {
                continue;
            }

            $path = '';

            foreach ($parts as $part) {
                // Static Segment
                if (is_string($part)) {
                    $path .= $part;
                    continue;
                }

                // Check If The Parameter Can Be Matched
                if ($this->debug && !preg_match("~^{$part[1]}$~", strval($params[$part[0]]))) {
                    throw new Exception("Value Of Parameter '{$part[0]}' Failed To Match Pattern '{$part[1]}'");
                }

                // Dynamic Segment
                $path .= $params[$part[0]];
            }

            return $path;
        }

        throw new Exception("Route '{$name}' Expects Parameters '" . implode(',', $missing) . "' But Received '" . implode(',', array_keys($params)) . "'");
    }


    public function url(string $name, array $params = []) : string
    {
        return "{$this->domain}{$this->uri($name, $params)}";
    }
}
