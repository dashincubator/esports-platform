<?php

namespace App\Commands\Event\Team\Member\Leave;

use App\Commands\Event\Team\Member\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage() : string
    {
        return 'You have left successfully!';
    }


    public function writeCannotLeaveDeleteMessage() : void
    {
        $this->error('You cannot leave when there is only 1 member on the team');
    }


    public function writeManagesTeamMessage() : void
    {
        $this->error('Team managers cannot leave, they must be kicked');
    }


    public function writeRosterLockedMessage(string $action = '') : void
    {
        $this->error('Team invites cannot be leave once a team has been locked');
    }
}
