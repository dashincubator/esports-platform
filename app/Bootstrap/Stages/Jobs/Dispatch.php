<?php

namespace App\Bootstrap\Stages\Jobs;

use Contracts\App\Jobs\Job;
use Contracts\Bootstrap\Application;
use Contracts\Container\Container;
use Contracts\Configuration\Configuration;
use Contracts\Pipeline\Pipeline;
use Closure;

class Dispatch
{

    private const JOB_METHOD = 'handle';


    private $config;

    private $container;

    private $pipeline;


    public function __construct(Configuration $config, Container $container, Pipeline $pipeline)
    {
        $this->config = $config;
        $this->container = $container;
        $this->pipeline = $pipeline;
    }


    public function handle(Application $app, Closure $next)
    {
        return $next(
            $this->pipeline
                ->send($app)
                //->through([])
                ->then(function() {
                    foreach ($this->config->get('app.jobs.dispatch') as $stage) {
                        $this->resolveJob($stage)->{self::JOB_METHOD}();
                    }
                })
                ->execute()
        );
    }


    private function resolveJob(string $class) : Job
    {
        return $this->container->resolve($class);
    }
}
