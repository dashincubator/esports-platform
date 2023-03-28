<?php

namespace App\Commands\Ladder\Match\Schedule\Update;

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
        $ids = $this->mapper->findActiveIdsForUpdateJob();

        foreach ($ids as $id) {
            $this->mapper->scheduleUpdateJob(compact('id'));
        }
    }
}
