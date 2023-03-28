<?php declare(strict_types=1);

namespace System\Jobs;

use Contracts\Container\Container;
use Contracts\Jobs\{Factory as Contract, Job};

class Factory implements Contract
{

    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function createJob(string $classname, string $method, array $parameters, bool $useLock = false) : Job
    {
        return $this->container->resolve(Job::class, [$classname, $method, $parameters, $useLock]);
    }
}
