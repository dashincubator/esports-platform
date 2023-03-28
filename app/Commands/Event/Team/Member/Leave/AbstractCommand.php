<?php

namespace App\Commands\Event\Team\Member\Leave;

use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\Team\AbstractMapper as TeamMapper;
use App\DataSource\Event\Team\Member\AbstractMapper as TeamMemberMapper;

abstract class AbstractCommand extends AbstractParent
{

    private $mapper;


    public function __construct(Filter $filter, TeamMapper $team, TeamMemberMapper $member)
    {
        $this->filter = $filter;
        $this->mapper = compact('member', 'team');
    }


    protected function run(int $team, int $user) : bool
    {
        $member = $this->mapper['member']->findByTeamAndUser($team, $user);

        if ($member->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $team = $this->mapper['team']->findById($member->getTeam());

            if ($team->isLocked()) {
                $this->filter->writeRosterLockedMessage();
            }
            elseif ($this->mapper['member']->countMembersOfTeam($team->getId()) < 2) {
                $this->filter->writeCannotLeaveDeleteMessage();
            }
            elseif ($member->managesTeam()) {
                $this->filter->writeManagesTeamMessage();
            }
        }

        if (!$this->filter->hasErrors()) {
            $this->mapper['member']->delete($member);
        }

        return $this->booleanResult();
    }
}
