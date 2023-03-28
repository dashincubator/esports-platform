<?php

namespace App\Commands\Event\Match\Report\Update;

use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\Match\AbstractMapper as MatchMapper;
use App\DataSource\Event\Match\Report\AbstractMapper as MatchReportMapper;
use App\DataSource\Event\Team\Member\AbstractMapper as TeamMemberMapper;

abstract class AbstractCommand extends AbstractParent
{

    private $mapper;


    public function __construct(Filter $filter, MatchMapper $match, MatchReportMapper $report, TeamMemberMapper $member)
    {
        $this->filter = $filter;
        $this->mapper = compact('match', 'member', 'report');
    }


    protected function run(int $id, int $placement, int $user) : bool
    {
        $report = $this->mapper['report']->findById($id);

        if ($report->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            // Validate Match Status
            $match = $this->mapper['match']->findById($report->getMatch());

            if (!$match->isReportable()) {
                $this->filter->writeCannotReportMessage();
            }

            // Member Auth Check
            $member = $this->mapper['member']->findByTeamAndUser($report->getTeam(), $user);

            if (!$member->managesMatches()) {
                $this->filter->writeUnauthorizedMemberMessage();
            }
        }

        if (!$this->filter->hasErrors()) {
            $report->placed($placement, $user);

            $this->mapper['report']->update($report);
            $this->mapper['match']->scheduleUpdateJob([
                'id' => $report->getMatch()
            ]);
        }

        return $this->booleanResult();
    }
}
