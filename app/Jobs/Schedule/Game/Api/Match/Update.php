<?php

namespace App\Jobs\Schedule\Game\Api\Match;

use App\DataSource\Game\Api\Match\Mapper;
use Contracts\App\Jobs\Job as Contract;

class Update implements Contract
{
 
    private $mapper;


    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }


    public function handle() : void
    {
        $this->mapper->scheduleUpdateJob();
    }
}
