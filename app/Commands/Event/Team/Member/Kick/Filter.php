<?php

namespace App\Commands\Event\Team\Member\Kick;

use App\Commands\Event\Team\Member\AbstractFilter;

class Filter extends AbstractFilter
{
 
    protected function getSuccessMessage() : string
    {
        return 'User kicked from team successfully!';
    }


    public function writeCannotKickDeleteMessage() : void
    {
        $this->error('You cannot kick the only member of the team');
    }


    public function writeRosterLockedMessage(string $action = '') : void
    {
        $this->error('Team invites cannot be kicked once a team has been locked');
    }
}
