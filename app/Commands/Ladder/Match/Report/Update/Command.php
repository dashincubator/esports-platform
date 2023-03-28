<?php

namespace App\Commands\Ladder\Match\Report\Update;

use App\Commands\Event\Match\Report\Update\{AbstractCommand, Filter};
use App\DataSource\Ladder\Match\Mapper as MatchMapper;
use App\DataSource\Ladder\Match\Report\Mapper as MatchReportMapper;
use App\DataSource\Ladder\Team\Member\Mapper as TeamMemberMapper;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, MatchMapper $match, MatchReportMapper $report, TeamMemberMapper $member)
    {
        parent::__construct($filter, $match, $report, $member);
    }
}
