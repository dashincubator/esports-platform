<?php

namespace App\Jobs\Queue;

use Contracts\App\Jobs\Job as Contract;
use Contracts\Jobs\Queue;

class ActivateDelayedJobs implements Contract
{

    private $queue;


    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }


    public function handle() : void
    {
        $this->queue->activateDelayedJobs('Jobs', 'DelayedJobs');
    }
}
