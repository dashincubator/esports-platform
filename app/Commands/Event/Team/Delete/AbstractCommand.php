<?php

namespace App\Commands\Event\Team\Delete;

use App\Commands\AbstractCommand as AbstractParent;
use App\Commands\Event\Team\Member\Delete\AbstractCommand as DeleteTeamMemberCommand;
use App\DataSource\Event\Match\Report\AbstractMapper as MatchReportMapper;
use App\DataSource\Event\Team\{AbstractEntity as TeamEntity, AbstractMapper as TeamMapper};

abstract class AbstractCommand extends AbstractParent
{

    private $command;

    private $mapper;


    public function __construct(DeleteTeamMemberCommand $member, AbstractFilter $filter, MatchReportMapper $report, TeamMapper $team)
    {
        $this->command = compact('member');
        $this->filter = $filter;
        $this->mapper = compact('report', 'team');
    }


    private function delete(TeamEntity ...$teams) : void
    {
        $this->mapper['team']->delete(...$teams);

        $ids = [];

        foreach ($teams as $team) {
            $ids[] = $team->getId();
        }

        $this->delegate($this->command['member'], [
            'teams' => $ids
        ]);
    }


    protected function deleteByEvent(int $event) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper['team']->findByEvent($event))
        );
    }


    protected function deleteById(int $id) : void
    {
        $team = $this->mapper['team']->findById($id);

        if ($team->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif ($team->isLocked()) {
            $this->filter->writeTeamsLockedMessage();
        }
        elseif (count($this->mapper['report']->findActiveMatchIdsByTeam($team->getId())) > 0) {
            $this->filter->writeActiveMatchesMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->delete($team);
        }
    }
}
