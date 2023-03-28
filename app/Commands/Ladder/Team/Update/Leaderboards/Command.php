<?php

namespace App\Commands\Ladder\Team\Update\Leaderboards;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\Mapper as LadderMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(LadderMapper $ladder, TeamMapper $team)
    {
        $this->mapper = compact('ladder', 'team');
    }


    public function run() : void
    {
        $ladders = $this->mapper['ladder']->findAll()->column('id');
 
        $this->mapper['team']->refreshLeaderboards(...$ladders);
    }
}
