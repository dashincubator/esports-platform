<?php

namespace App\Commands\Ladder\Match\Schedule\Start;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\Match\Mapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper; 
    }


    public function run() : void
    {
        $ids = $this->mapper->findUpcomingIdsForStartJob();

        foreach ($ids as $id) {
            $this->mapper->scheduleStartJob(compact('id'));
        }
    }
}
