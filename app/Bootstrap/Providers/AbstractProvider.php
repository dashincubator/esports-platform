<?php

namespace App\Bootstrap\Providers;

use Contracts\Bootstrap\Provider as Contract;
use Contracts\Configuration\Configuration;
use Contracts\Container\Container;

abstract class AbstractProvider implements Contract
{

    protected $config;

    protected $container;


    public function __construct(Configuration $config, Container $container)
    {
        $this->config = $config;
        $this->container = $container;
    }


    public function boot() : void
    { }


    public function register() : void
    { }
}
