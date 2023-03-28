<?php

namespace App\Commands\Event\Team\Invite\DeleteExpired;

use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\Team\Member\AbstractMapper as TeamMemberMapper;

abstract class AbstractCommand extends AbstractParent
{

    private $minutes;

    private $mapper;


    public function __construct(TeamMemberMapper $mapper, int $minutes)
    {
        $this->mapper = $mapper;
        $this->minutes = $minutes;
    }


    protected function run() : void
    {
        $this->mapper->delete(...$this->mapper->findExpiredInvites($this->minutes));
    }
}
