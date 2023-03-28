<?php declare(strict_types=1);

namespace Contracts\Routing;

interface Matcher
{

    /**
     *  Match RouteContext To Route In Collection
     *
     *  @return Route Mapped Route If Found, Otherwise Return Fallback Route
     */
    public function match(string $fallback, string $host, string $method, string $uri) : Route;
}
