<?php declare(strict_types=1);

namespace System\Container\Bindings;

use Closure;

class Factory
{

    // Factory Callable
    private $factory;

    // Should Binding Be Resolved As Singleton
    private $resolveAsSingleton = false;


    public function __construct(Closure $factory, bool $resolveAsSingleton)
    {
        $this->factory = $factory;
        $this->resolveAsSingleton = $resolveAsSingleton;
    }


    public function getFactory() : Closure
    {
        return $this->factory;
    }


    public function resolveAsSingleton() : bool
    {
        return $this->resolveAsSingleton;
    }
}
