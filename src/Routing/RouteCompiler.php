<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Fork Of FastRoute Version 2.0 By Nikic - https://github.com/nikic/FastRoute
 *
 */

namespace System\Routing;

use Contracts\Routing\{RouteCollection, RouteCompiler as Contract};
use Exception;

class RouteCompiler implements Contract
{

    // Group Chunk Size
    private const CHUNK_SIZE = 50;


    private function buildDynamicRoutes(RouteCollection $collection) : array
    {
        $dynamic = [];

        foreach ($collection as $route) {
            $host = $route->getHost();
            $name = $route->getName();

            foreach ($route->getParsedData() as $data) {
                if (!$this->isDynamicRoute($data ?? [])) {
                    continue;
                }

                foreach ($route->getHttpMethods() as $method) {
                    $data = $this->buildDynamicRouteRegex($name, $data);

                    if (isset($dynamic[$method][$host][$data['regex']])) {
                        throw new Exception("Cannot Register Two Routes Matching Host '{$host}' Regex '{$data['regex']}' Method '{$method}'");
                    }

                    $dynamic[$method][$host][$data['regex']] = $data;
                }
            }
        }

        return $this->buildDynamicRouteData($dynamic);
    }


    private function buildDynamicRouteChunk(array $routes) : array
    {
        $key = 0;
        $map = [];
        $regexes = [];

        foreach ($routes as $route) {
            $map[$key] = [$route['name'], $route['variables']];
            $regexes[] = "{$route['regex']}(*MARK:{$key})";

            $key++;
        }

        return ['~^(?|' . implode('|', $regexes) . ')$~' => $map];
    }


    private function buildDynamicRouteData(array $routes) : array
    {
        $data = [];

        foreach ($routes as $method => $hosts) {
            foreach ($hosts as $host => $routes) {

                foreach (array_chunk($routes, self::CHUNK_SIZE) as $chunk) {
                    $data[$method][$host] = array_merge($data[$method][$host] ?? [], $this->buildDynamicRouteChunk($chunk));
                }

            }
        }

        return $data;
    }


    private function buildDynamicRouteRegex(string $name, array $data) : array
    {
        $regex = '';
        $variables = [];

        foreach ($data as $segment) {
            if (is_string($segment)) {
                $regex .= preg_quote($segment, '~');
                continue;
            }

            list($key, $part) = $segment;

            if (isset($variables[$key])) {
                throw new Exception("Cannot Use The Same Variable Twice '{$key}' For Route Name '{$name}'");
            }

            if ($this->regexHasCapturingGroups($part)) {
                throw new Exception("Regex '{$part}' For Parameter '{$key}' Contains A Capturing Group For Route Name '{$name}'");
            }

            $regex .= "({$part})";
            $variables[] = $key;
        }

        return compact('regex', 'name', 'variables');
    }


    private function buildStaticRoutes(RouteCollection $collection) : array
    {
        $static = [];

        foreach ($collection as $route) {
            $host = $route->getHost();
            $name = $route->getName();

            foreach ($route->getParsedData() as $data) {
                if (!$this->isStaticRoute($data ?? [])) {
                    continue;
                }

                $uri = $data[0];

                foreach ($route->getHttpMethods() as $method) {
                    if (isset($static[$method][$host][$uri])) {
                        throw new Exception("Cannot Register Two Routes Matching Host '{$host}' Method '{$method}' Path '{$uri}'");
                    }

                    $static[$method][$host][$uri] = $name;
                }
            }
        }

        return $static;
    }


    public function compile(RouteCollection $collection) : array
    {
        return [$this->buildDynamicRoutes($collection), $this->buildStaticRoutes($collection)];
    }


    private function isStaticRoute(array $data) : bool
    {
        return count($data) === 1 && is_string($data[0]);
    }


    private function isDynamicRoute(array $data) : bool
    {
        return count($data) > 1;
    }


    private function regexHasCapturingGroups(string $regex) : bool
    {
        // Regex Needs At Least 1 ( To Contain A Capturing Group
        if (mb_strpos($regex, '(') === false) {
            return false;
        }

        // Semi-Accurate Detection For Capturing Groups
        return (bool) preg_match(
            '~
                (?:
                    \(\?\(
                    | \[ [^\]\\\\]* (?: \\\\ . [^\]\\\\]* )* \]
                    | \\\\ .
                ) (*SKIP)(*FAIL) |
                \(
                (?!
                    \? (?! <(?![!=]) | P< | \' )
                    | \*
                )
            ~x',
            $regex
        );
    }
}
