<?php

namespace App\Commands\Ladder\Team\Delete;

use App\Commands\Event\Team\Delete\AbstractCommand;
use App\Commands\Ladder\Team\Member\Delete\Command as DeleteTeamMemberCommand;
use App\DataSource\Ladder\Match\Report\Mapper as MatchReportMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;

class Command extends AbstractCommand
{

    public function __construct(DeleteTeamMemberCommand $member, Filter $filter, MatchReportMapper $report, TeamMapper $team)
    {
        parent::__construct($member, $filter, $report, $team);
    }


    protected function run(?int $id, ?int $ladder) : bool
    {
        if ($id) {
            $this->deleteById($id);
        }
        elseif ($ladder) {
            $this->deleteByEvent($ladder);
        }

        return $this->booleanResult();
    }
}
