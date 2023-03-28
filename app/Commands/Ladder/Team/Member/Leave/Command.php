<?php

namespace App\Commands\Ladder\Team\Member\Leave;

use App\Commands\Event\Team\Member\Leave\{AbstractCommand, Filter};
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\DataSource\Ladder\Team\Member\Mapper as TeamMemberMapper;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, TeamMapper $team, TeamMemberMapper $member)
    {
        parent::__construct($filter, $team, $member);
    }
}
