<?php

namespace App\Commands\Ladder\Team\Create;

use App\Commands\Event\Team\Create\{AbstractCommand, Filter};
use App\DataSource\Ladder\Mapper as EventMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\DataSource\Ladder\Team\Member\Mapper as TeamMemberMapper;

class Command extends AbstractCommand
{

    public function __construct(EventMapper $event, Filter $filter, TeamMapper $team, TeamMemberMapper $member)
    {
        parent::__construct($event, $filter, $team, $member);
    }
}
