<?php

namespace App\Bootstrap\Stages;

use Contracts\Bootstrap\{Application, Provider};
use Contracts\Configuration\Configuration;
use Contracts\Container\Container;
use Closure;

class BootProviders
{

    private const METHOD = 'boot';


    private $config;

    private $container;


    public function __construct(Configuration $config, Container $container)
    {
        $this->config = $config;
        $this->container = $container;
    }


    public function handle(Application $app, Closure $next)
    {
        foreach ($this->config->get('app.providers') as $provider) {
            $this->resolveApplicationProvider($provider)->{self::METHOD}();
        }

        return $next($app);
    }


    private function resolveApplicationProvider(string $classname) : Provider
    {
        return $this->container->resolve($classname);
    }
}
