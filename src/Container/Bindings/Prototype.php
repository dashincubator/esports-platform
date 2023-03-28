<?php declare(strict_types=1);

namespace System\Container\Bindings;

class Prototype
{

    // Name Of Concrete Class
    private $concreteClass;

    // Constructor Parameters
    private $constructorParameters = [];

    // Should Binding Be Resolved As Singleton
    private $resolveAsSingleton = false;


    public function __construct(string $concreteClass, array $constructorParameters, bool $resolveAsSingleton)
    {
        $this->concreteClass = $concreteClass;
        $this->constructorParameters = $constructorParameters;
        $this->resolveAsSingleton = $resolveAsSingleton;
    }


    public function getConcreteClass() : string
    {
        return $this->concreteClass;
    }


    public function getConstructorParameters() : array
    {
        return $this->constructorParameters;
    }


    public function resolveAsSingleton() : bool
    {
        return $this->resolveAsSingleton;
    }
}
