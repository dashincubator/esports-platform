<?php

namespace App\Commands\Ladder\Team\Invite\Create;

use App\Commands\Event\Team\Invite\Create\{AbstractCommand, Filter};
use App\DataSource\Ladder\{Entity as EventEntity, Mapper as EventMapper};
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\DataSource\Ladder\Team\Member\Mapper as TeamMemberMapper;

class Command extends AbstractCommand
{

    public function __construct(EventMapper $event, Filter $filter, TeamMapper $team, TeamMemberMapper $member)
    {
        parent::__construct($event, $filter, $team, $member);
    }


    protected function isRosterFull(int $count, EventEntity $event) : bool
    {
        return $count >= $event->getMaxPlayersPerTeam();
    }
}
