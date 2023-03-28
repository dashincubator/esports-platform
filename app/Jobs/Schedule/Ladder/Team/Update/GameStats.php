<?php

namespace App\Jobs\Schedule\Ladder\Team\Update;

use App\Commands\Ladder\Team\Schedule\Update\GameStats\Command;
use Contracts\App\Jobs\Job as Contract;

class GameStats implements Contract
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
