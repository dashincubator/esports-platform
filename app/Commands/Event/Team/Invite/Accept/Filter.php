<?php

namespace App\Commands\Event\Team\Invite\Accept;

use App\Commands\Event\Team\Invite\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('accepted');
    }


    public function writeAlreadyOnTeamMessage() : void
    {
        $this->error('You are already on a team for this event');
    }


    public function writeRosterFullMessage(string $action = '') : void
    {
        parent::writeRosterFullMessage('accepted');
    }


    public function writeRosterLockedMessage(string $action = '') : void
    {
        parent::writeRosterLockedMessage('accepted');
    }
}
