<?php declare(strict_types=1);

namespace System\Container\Bindings;

class Instance
{

    // Object Instance
    private $instance;

    // Should Binding Be Resolved As Singleton
    private $resolveAsSingleton = true;


    public function __construct($instance)
    {
        $this->instance = $instance;
    }


    public function getInstance() : object
    {
        return $this->instance;
    }


    public function resolveAsSingleton() : bool
    {
        return $this->resolveAsSingleton;
    }
}
