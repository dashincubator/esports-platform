<?php

namespace App\Bootstrap\Stages\Http;

use Contracts\Container\Container;
use Contracts\Http\{Middleware, Request, Response};
use Contracts\Pipeline\Pipeline;
use Closure;

class Dispatch
{

    private $container;

    private $pipeline;


    public function __construct(Container $container, Pipeline $pipeline)
    {
        $this->container = $container;
        $this->pipeline = $pipeline;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $attributes = $request->getAttributes();

        return $next(
            $this->pipeline
                ->send($request)
                ->through(
                    $this->resolveMiddlewareStages(
                        $attributes->get('route.middleware.classnames'),
                        $attributes->get('route.middleware.method')
                    )
                )
                ->then(function(Request $request) use ($attributes) {
                    return $this->container->resolve
                        ($attributes->get('route.class.name'))->{$attributes->get('route.class.method')}(
                            $request,
                            ...array_values($attributes->get('route.variables')
                        )
                    );
                })
                ->execute()
        );
    }


    private function resolveMiddlewareStages(array $classnames, string $method)
    {
        $stages = [];

        foreach ($classnames as $classname) {
            $stages[] = function(Request $request, Closure $next) use ($classname, $method) {
                return $this->resolveMiddlewareStage($classname)->{$method}($request, $next);
            };
        }

        return $stages;
    }


    private function resolveMiddlewareStage(string $classname) : Middleware
    {
        return $this->container->resolve($classname);
    }
}
