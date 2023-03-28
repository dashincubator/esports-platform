<?php

namespace App\Commands\Event\Team\Invite\Create;

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
        $team = $this->mapper['team']->findById($team);

        if ($team->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $event = $this->mapper['event']->findById($team->getEventId());

            if ($event->isRegistrationClosed() || $team->isLocked()) {
                $this->filter->writeRosterLockedMessage();
            }
            elseif (!$this->mapper['member']->findInviteByTeamAndUser($team->getId(), $user)->isEmpty()) {
                $this->filter->writeInviteAlreadyExistsMessage();
            }
            elseif ($this->isRosterFull($this->mapper['member']->countMembersOfTeam($team->getId()), $event)) {
                $this->filter->writeRosterFullMessage();
            }
            elseif ($this->mapper['member']->onExistingTeam($team->getEventId(), $user)) {
                $this->filter->writeAlreadyOnTeamMessage();
            }
        }

        if (!$this->filter->hasErrors()) {
            $member = $this->mapper['member']->create([
                'team' => $team->getId(),
                'user' => $user
            ]);
            $member->invite();

            $this->mapper['member']->insert($member);
        }

        return $this->booleanResult();
    }
}
