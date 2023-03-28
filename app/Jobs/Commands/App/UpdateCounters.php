<?php

namespace App\Jobs\Commands\App;

use App\Commands\App\Counters\Command;
use Contracts\App\Jobs\Job as Contract;

class UpdateCounters implements Contract
{

    protected $command;


    public function __construct(Command $command)
    {
        $this->command = $command;
    }


    public function handle() : void
    {
        $this->command->execute();
    }
}
