<?php

namespace App\Commands\Event\Team\Invite\Decline;

use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\Team\Member\AbstractMapper as Mapper;

abstract class AbstractCommand extends AbstractParent
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(int $team, int $user) : bool
    {
        $invite = $this->mapper->findInviteByTeamAndUser($team, $user);

        if ($invite->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->mapper->delete($invite);
        }

        return $this->booleanResult();
    }
}
