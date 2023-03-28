<?php

namespace App\Commands\Event\Team\Create;

use Closure;
use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\AbstractMapper as EventMapper;
use App\DataSource\Event\Team\{AbstractEntity as TeamEntity, AbstractMapper as TeamMapper};
use App\DataSource\Event\Team\Member\AbstractMapper as TeamMemberMapper;

abstract class AbstractCommand extends AbstractParent
{

    protected $mapper;


    public function __construct(EventMapper $event, Filter $filter, TeamMapper $team, TeamMemberMapper $member)
    {
        $this->filter = $filter;
        $this->mapper = compact('event', 'team', 'member');
    }


    protected function run(int $event, string $name, int $user) : TeamEntity
    {
        $event = $this->mapper['event']->findById($event);

        if ($event->isEmpty()) {
            $this->filter->writeUnkownErrorMessage();
        }
        else {
            $team = $this->mapper['team']->create(compact('name'));
            $team->createdBy($user);
            $team->joined($event->getId());

            if ($event->isRegistrationClosed()) {
                $this->filter->writeRegistrationClosedMessage();
            }
            elseif ($this->mapper['member']->onExistingTeam($event->getId(), $user)) {
                $this->filter->writeAlreadyOnTeamMessage($event->getId());
            }
            elseif (!$this->mapper['team']->isUniqueNameAndSlug($event->getId(), $team->getName(), $team->getSlug())) {
                $this->filter->writeNameUnavailableMessage();
            }
        }

        if (!$this->filter->hasErrors()) {
            return $this->mapper['team']->transaction(function() use ($user, $team) {
                $this->mapper['team']->insert($team);

                $this->mapper['member']->transaction(function() use ($user, $team) {
                    $member = $this->mapper['member']->create([
                        'team' => $team->getId(),
                        'user' => $user
                    ]);
                    $member->founder();

                    $this->mapper['member']->insert($member);
                });

                $this->filter->writeSuccessMessage();

                return $team;
            });
        }

        return $this->mapper['team']->create();
    }
}
