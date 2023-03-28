<?php

namespace App\Bootstrap\Providers\Contracts;

use Contracts\IO\FileSystem;
use Contracts\Cache\Cache;
use Contracts\Routing\{Factory, Matcher, RouteCollection, RouteCompiler, RouteParser, Router};
use App\Bootstrap\Providers\AbstractProvider;

class RoutingProvider extends AbstractProvider
{

    private const COMPILED_ROUTES = 'routescompiled';

    private const ROUTES = 'routes';


    private function buildCompiledRoutes() : array
    {
        $compiler = $this->container->resolve(RouteCompiler::class);
        $routes = $this->container->resolve(RouteCollection::class);

        return $compiler->compile($routes);
    }


    private function loadCompiledRoutesFromCache() : array
    {
        $cache = $this->container->resolve(Cache::class);

        if ($this->config->get('app.debug')) { 
            $cache->delete(self::COMPILED_ROUTES);
        }

        return $cache->get(self::COMPILED_ROUTES, function() use ($cache) {
            $data = $this->buildCompiledRoutes();

            $cache->set(self::COMPILED_ROUTES, $data);

            return $data;
        });
    }


    private function loadRoutesFromCache(string $concrete) : RouteCollection
    {
        $cache = $this->container->resolve(Cache::class);

        if ($this->config->get('app.debug')) {
            $cache->delete(self::ROUTES);
        }

        return $cache->get(self::ROUTES, function() use ($cache, $concrete) {
            $data = $this->loadRoutesFromDirectory($concrete);

            $cache->set(self::ROUTES, $data);

            return $data;
        });
    }


    private function loadRoutesFromDirectory(string $concrete) : RouteCollection
    {
        $filesystem = $this->container->resolve(FileSystem::class);
        $router = $this->container->resolve(Router::class, [
            $this->container->resolve(Factory::class, [$this->config->get('app.middleware', [])]),
            $this->container->resolve($concrete)
        ]);

        $files = $filesystem->getFiles($this->config->get('paths.routes'));

        // $r Is Accessible By Route Files To Build Routes
        foreach ($files as $file) {
            $filesystem->require($file, ['r' => $router]);
        }

        return $router->getRoutes();
    }


    public function register() : void
    {
        $this->registerRouterBinding();

        $this->registerMatcherBinding();
        $this->registerRouteParserBinding();
        $this->registerRoutesBinding();
    }


    private function registerMatcherBinding() : void
    {
        $concrete = $this->container->getAlias(Matcher::class);

        $this->container->singleton(Matcher::class, function() use ($concrete) {
            return $this->container->resolve($concrete, $this->loadCompiledRoutesFromCache());
        });
    }


    private function registerRouterBinding() : void
    {
        $this->container->bind(Router::class, null, [$this->config->get('app.debug'), $this->config->get('app.host')]);
    }


    private function registerRoutesBinding() : void
    {
        $concrete = $this->container->getAlias(RouteCollection::class);

        $this->container->singleton(RouteCollection::class, function() use ($concrete) {
            return $this->loadRoutesFromCache($concrete);
        });
    }


    private function registerRouteParserBinding() : void
    {
        $this->container->singleton(RouteParser::class, null, [$this->config->get('contracts.route.rules')]);
    }
}
