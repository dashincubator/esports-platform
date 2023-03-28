<?php

namespace App\Commands\Event\Team\Invite\Accept;

use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\AbstractMapper as EventMapper;
use App\DataSource\Event\Team\AbstractMapper as TeamMapper;
use App\DataSource\Event\Team\Member\AbstractMapper as TeamMemberMapper;

abstract class AbstractCommand extends AbstractParent
{

    private $mapper;


    public function __construct(EventMapper $event, Filter $filter, TeamMapper $team, TeamMemberMapper $member)
    {
        $this->filter = $filter;
        $this->mapper = compact('event', 'member', 'team');
    }


    protected function run(int $team, int $user) : bool
    {
        $invite = $this->mapper['member']->findInviteByTeamAndUser($team, $user);

        if ($invite->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $team = $this->mapper['team']->findById($invite->getTeam());
            $event = $this->mapper['event']->findById($team->getEventId());

            if ($event->isRegistrationClosed() || $team->isLocked()) {
                $this->filter->writeRosterLockedMessage();
            }
            elseif ($this->mapper['member']->onExistingTeam($event->getId(), $invite->getUser())) {
                $this->filter->writeAlreadyOnTeamMessage();
            }
            elseif ($this->isRosterFull($this->mapper['member']->countMembersOfTeam($team->getId()), $event)) {
                $this->filter->writeRosterFullMessage();
            }
        }

        if (!$this->filter->hasErrors()) {
            $invite->accept();
            $this->mapper['member']->update($invite);
        }

        return $this->booleanResult();
    }
}
