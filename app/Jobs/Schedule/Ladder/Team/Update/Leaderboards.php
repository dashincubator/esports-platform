<?php

namespace App\Jobs\Schedule\Ladder\Team\Update;

use App\DataSource\Ladder\Team\Mapper;
use Contracts\App\Jobs\Job as Contract;

class Leaderboards implements Contract
{

    private $mapper;


    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }


    public function handle() : void
    {
        $this->mapper->scheduleUpdateLeaderboardsJob();
    }
}
