<?php declare(strict_types=1);

namespace System\Routing;

use Contracts\Routing\{Factory, Matcher as Contract, Route, RouteCollection};

class Matcher implements Contract
{

    // Route Collection Matching Compiled Route Data
    private $collection;

    // Compiled Dynamic Route Data
    private $dynamic = [];

    // Routing Factory
    private $factory;

    // Compiled Static Route Data
    private $static = [];


    public function __construct(Factory $factory, RouteCollection $collection, array $dynamic, array $static)
    {
        $this->collection = $collection;
        $this->dynamic = $dynamic;
        $this->factory = $factory;
        $this->static = $static;
    }


    public function match(string $fallback, string $host, string $method, string $uri) : Route
    {
        // Static Route Matching
        if ($name = $this->static[$method][$host][$uri] ?? '') {
            return $this->collection->get($name);
        }

        // Dynamic Route Matching
        $dynamic = $this->dynamic[$method][$host] ?? [];

        foreach ($dynamic as $regex => $map) {
            if (!preg_match($regex, $uri, $matches)) {
                continue;
            }

            list($name, $keys) = $map[$matches['MARK']];

            $i = 0;
            $variables = [];

            foreach ($keys as $key) {
                $variables[$key] = $matches[++$i];
            }

            return $this->collection->get($name)->variables($variables);
        }

        // Return Fallback
        return $this->collection->get($fallback);
    }
}
