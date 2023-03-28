<?php declare(strict_types=1);

namespace App\Bootstrap;

use Contracts\Bootstrap\Application as Contract;
use Contracts\Configuration\Configuration;
use Contracts\Container\Container;
use Contracts\Pipeline\Pipeline;
use Closure;

class Application implements Contract
{

    private const STAGE_METHOD = 'handle';


    private $container;

    private $dispatched = false;


    /**
     *  @param array $app 'config/app.php'
     *  @param array $bindings
     *  @param array $paths Application Paths
     */
    public function __construct(array $app, array $bindings, array $paths)
    {
        $this->registerContainerWithBindings($bindings);
        $this->registerConfigurationWithBootData($app, $paths);
    }


    public function dispatch(array $stages)
    {
        if ($this->dispatched) {
            return;
        }

        $this->dispatched = true;

        return $this->container->resolve(Pipeline::class)
            ->send($this)
            ->through($this->resolveStages($stages))
            ->execute();
    }


    private function registerConfigurationWithBootData(array $app, array $paths) : void
    {
        $this->container->singleton(Configuration::class, null, [compact('app', 'paths')]);
    }


    private function registerContainerWithBindings(array $bindings) : void
    {
        $this->container = new $bindings[Container::class]();

        foreach ($bindings as $abstract => $concrete) {
            $this->container->bind($abstract, $concrete);
        }

        $this->container->singleton(Container::class, $this->container);
    }


    private function resolveStages(array $stages) : array
    {
        foreach ($stages as $index => $stage) {
            $stages[$index] = function($input, Closure $next) use ($stage) {
                return $this->container->resolve($stage)->{self::STAGE_METHOD}($input, $next);
            };
        }

        return $stages;
    }
}
