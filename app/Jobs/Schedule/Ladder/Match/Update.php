<?php

namespace App\Jobs\Schedule\Ladder\Match;

use App\Commands\Ladder\Match\Schedule\Update\Command;
use Contracts\App\Jobs\Job as Contract;

class Update implements Contract
{

    private $command;


    public function __construct(Command $command)
    {
        $this->command = $command;
    }


    public function handle() : void
    {
        $this->command->execute();
    }
}
