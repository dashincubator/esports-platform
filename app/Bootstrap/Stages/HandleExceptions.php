<?php

namespace App\Bootstrap\Stages;

use Contracts\Bootstrap\Application;
use Contracts\Configuration\Configuration;
use Contracts\Container\Container;
use Contracts\Debug\{Debug, Logger, Renderer};
use Closure;

class HandleExceptions
{

    private $config;

    private $container;


    public function __construct(Configuration $config, Container $container)
    {
        $this->config = $config;
        $this->container = $container;
    }


    public function handle(Application $app, Closure $next)
    {
        $this->container->resolve(Debug::class, [
            $this->container->resolve(Logger::class, [$this->config->get('app.exceptions.errorlog')]),
            $this->container->resolve(Renderer::class, [
                $this->config->get('app.debug'),
                $this->config->get('app.exceptions.development.html'),
                $this->config->get('app.exceptions.development.json'),
                $this->config->get('app.exceptions.production.html'),
                $this->config->get('app.exceptions.production.json')
            ])
        ])
        ->register();

        return $next($app);
    }
}
