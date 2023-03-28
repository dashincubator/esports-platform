<?php declare(strict_types=1);

namespace System\Container;

use Contracts\Container\{Container as Contract, ContextualBindingBuilder};
use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container implements Contract
{

    // Collection Of Bindings
    private $bindings = [];

    // Cache Of Reflection Constructors And Their Parameters
    private $cache = [];

    // Collection Of Contextual Binding Data
    private $contextual = [];


    public function addContextualBinding(string $target, string $abstract, $implementation) : void
    {
        $this->contextual[$target][$abstract] = $implementation;
    }


    public function bind(string $abstract, $concrete = null, array $parameters = [], bool $resolveAsSingleton = false) : void
    {
        $concrete = $concrete ?? $this->getAlias($abstract);

        if ($concrete instanceof Closure) {
            $binding = new Bindings\Factory($concrete, $resolveAsSingleton);
        }
        elseif (is_object($concrete)) {
            $binding = new Bindings\Instance($concrete);
        }
        elseif (is_string($concrete)) {
            $binding = new Bindings\Prototype($concrete, $parameters, $resolveAsSingleton);
        }
        else {
            throw new Exception("IoC Container Received An Invalid Value '{$concrete}' For Abstract Key '{$abstract}'");
        }

        $this->bindings[$abstract] = $binding;
    }


    public function getAlias(string $abstract) : string
    {
        $binding = $this->getBinding($abstract);

        if (!is_null($binding) && get_class($binding) === Bindings\Prototype::class) {
            return $binding->getConcreteClass();
        }

        return $abstract;
    }


    private function getBinding(string $abstract)
    {
        return $this->bindings[$abstract] ?? null;
    }


    private function getContextualBinding(string $target, string $abstract)
    {
        $binding = $this->contextual[$target][$abstract] ?? null;

        if (is_null($binding)) { /* Do Nothing */ }
        elseif ($binding instanceof Closure) {
            $binding = $binding($this);
        }
        elseif (is_string($binding)) {
            $binding = $this->resolve($binding);
        }

        return $binding;
    }


    public function has(string $abstract) : bool
    {
        return isset($this->bindings[$abstract]);
    }


    public function resolve(string $abstract, array $with = [])
    {
        $binding = $this->getBinding($abstract);

        if (is_null($binding)) {
            return $this->resolveClass($abstract, $with);
        }

        switch(get_class($binding)) {
            case Bindings\Factory::class:
                // Some Classes Do Not Implement A Contract But Will Need A Factory
                // To Resolve Dependencies. Binding Is Unset To Enable Instantiation
                // Within The Factory.
                $this->unbind($abstract);

                $instance = ($binding->getFactory())(...array_merge([$this], $with));

                // Rebind Original Binding For Next Resolver
                if (!$binding->resolveAsSingleton()) {
                    $this->bindings[$abstract] = $binding;
                }
                break;
            case Bindings\Instance::class:
                $instance = $binding->getInstance();
                break;
            case Bindings\Prototype::class:
                $instance = $this->resolveClass(
                    $binding->getConcreteClass(),
                    array_merge((array) $binding->getConstructorParameters(), $with)
                );
                break;
            default:
                throw new Exception("Invalid IoC Container Binding Type Received For Abstract Key '{$abstract}'");
                break;
        }

        if ($binding->resolveAsSingleton()) {
            $this->singleton($abstract, $instance);
        }

        return $instance;
    }


    private function resolveClass(string $class, array $with = [])
    {
        if (!isset($this->cache[$class])) {
            $reflection = new ReflectionClass($class);

            if (!$reflection->isInstantiable()) {
                throw new Exception("'{$class}' Is Not Instantiable");
            }

            $this->cache[$class] = [
                ($constructor = $reflection->getConstructor()),
                ($parameters  = !is_null($constructor) ? $constructor->getParameters() : [])
            ];
        }

        list($constructor, $parameters) = $this->cache[$class];

        if (is_null($constructor)) {
            return new $class();
        }

        return new $class(...$this->resolveClassParameters($class, $with, $parameters));
    }


    private function resolveClassParameters(string $class, array $with, array $parameters)
    {
        $resolved = [];

        foreach ($parameters as $parameter) {
            $object = $parameter->getClass();

            if (!is_null($object)) {
                if ($parameter->isVariadic()) {
                    return array_merge($resolved, $with);
                }

                $resolved[] = $this->resolveObjectParameter($class, $object->getName(), $with);
            }
            else {
                $resolved[] = $this->resolvePrimitiveParameter($parameter, $with);
            }
        }

        return $resolved;
    }


    private function resolveObjectParameter(string $class, string $dependency, array &$with = [])
    {
        // Look For Contextual Bindings In '$with' Parameters
        $concrete = $this->getAlias($dependency);

        foreach ($with as $index => $value) {
            if ($value instanceof $concrete || $value instanceof $dependency) {
                return array_splice($with, $index, 1)[0];
            }
        }

        // Try Contextual Binding
        $value = $this->getContextualBinding($class, $dependency);

        // Resolve New Instance Of Parameter
        if (is_null($value)) {
            $value = $this->resolve($dependency);
        }

        return $value;
    }


    private function resolvePrimitiveParameter(ReflectionParameter $parameter, array &$with = [])
    {
        if (count($with) > 0) {
            return array_shift($with);
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new Exception("IoC Container Primitive Parameter '{$parameter->getName()}' Has No Default Value In {$parameter->getDeclaringClass()->getName()}::{$parameter->getDeclaringFunction()->getName()}()");
    }


    public function singleton(string $abstract, $concrete = null, array $parameters = []) : void
    {
        $this->bind($abstract, $concrete, $parameters, true);
    }


    public function unbind($abstracts) : void
    {
        foreach ((array) $abstracts as $abstract) {
            unset($this->bindings[$abstract]);
        }
    }


    public function when(string ...$abstracts) : ContextualBindingBuilder
    {
        foreach ($abstracts as $index => $abstract) {
            $abstracts[$index] = $this->getAlias($abstract);
        }

        return $this->resolve(ContextualBindingBuilder::class, [$abstracts]);
    }
}
