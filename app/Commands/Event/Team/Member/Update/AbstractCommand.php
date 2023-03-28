<?php

namespace App\Commands\Event\Team\Member\Update;

use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\Team\Member\AbstractMapper as TeamMemberMapper;

abstract class AbstractCommand extends AbstractParent
{

    private $mapper;


    public function __construct(Filter $filter, TeamMemberMapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(int $managesMatches, int $managesTeam, int $team, int $user) : bool
    {
        $member = $this->mapper->findByTeamAndUser($team, $user);

        if ($member->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $member->fill(compact('managesMatches', 'managesTeam'));
            $this->mapper->update($member);
        }

        return $this->booleanResult();
    }
}
