<?php declare(strict_types=1);

namespace System\Container;

use Contracts\Container\{Container, ContextualBindingBuilder as Contract};

class ContextualBindingBuilder implements Contract
{

    // Target For Contextual Binding
    private $concrete;

    // Container Instance
    private $container;

    // Parameters To Watch For
    private $needs;


    public function __construct(Container $container, array $concrete)
    {
        $this->concrete  = $concrete;
        $this->container = $container;
    }


    public function give($implementation) : void
    {
        foreach ($this->concrete as $class) {
            $this->container->addContextualBinding($class, $this->needs, $implementation);
        }
    }


    public function needs(string $abstract) : Contract
    {
        $this->needs = $abstract;

        return $this;
    }
}
