<?php

namespace App\Commands\Event\Team\Invite\Create;

use App\Commands\Event\Team\Invite\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('created');
    }


    public function writeAlreadyOnTeamMessage() : void
    {
        $this->error('User is already on a team for this event');
    }


    public function writeInviteAlreadyExistsMessage() : void
    {
        $this->error('Active team invite already exists');
    }


    public function writeRosterFullMessage(string $action = '') : void
    {
        parent::writeRosterFullMessage('created');
    }


    public function writeRosterLockedMessage(string $action = '') : void
    {
        parent::writeRosterLockedMessage('created');
    }
}
