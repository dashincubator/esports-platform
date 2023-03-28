<?php

namespace App\Services\Auth\Event;

use App\DataSource\Event\Team\Member\AbstractMapper;

abstract class AbstractTeam
{

    private $mapper;


    public function __construct(AbstractMapper $mapper)
    {
        $this->mapper = $mapper;
    }


    public function edit(int $team, int $user) : bool
    {
        $member = $this->mapper->findByTeamAndUser($team, $user);

        if ($member->isEmpty()) {
            return false;
        }

        if (!$member->managesTeam()) {
            return false;
        }

        return true;
    }


    public function matches(int $team, int $user) : bool
    {
        $member = $this->mapper->findByTeamAndUser($team, $user);

        if ($member->isEmpty()) {
            return false;
        }

        if (!$member->managesMatches()) {
            return false;
        }

        return true;
    }
}
