<?php

namespace App\Jobs\Schedule\Ladder\Match;

use App\Commands\Ladder\Match\Schedule\Start\Command;
use Contracts\App\Jobs\Job as Contract;

class Start implements Contract
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
