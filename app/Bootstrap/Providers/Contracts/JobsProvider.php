<?php

namespace App\Bootstrap\Providers\Contracts;

use App\Bootstrap\Providers\AbstractProvider;
use Contracts\Jobs\Worker;

class JobsProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerWorkerBinding();
    }


    private function registerWorkerBinding() : void
    {
        $this->container->singleton(Worker::class, null, [($this->config->get('contracts.jobs.worker.expire') * 60)]);
    }
}
