<?php

namespace App\Jobs\Queue;

use Contracts\App\Jobs\Job as Contract;
use Contracts\Jobs\Worker;

class AddWorker implements Contract
{

    private $worker;


    public function __construct(Worker $worker)
    {
        $this->worker = $worker;
    }


    public function handle() : void
    {
        $this->worker->execute('Jobs');
    }
}
