<?php

namespace App\Commands\Event\Team\Delete;

use App\Commands\AbstractFilter as AbstractParent;

abstract class AbstractFilter extends AbstractParent
{

    protected function getSuccessMessage() : string
    {
        return 'Team deleted successfully!';
    }


    public function writeActiveMatchesMessage() : void
    {
        $this->error('This team cannot be deleted until all matches have been finalized');
    }


    public function writeTeamsLockedMessage() : void
    {
        $this->error('Teams cannot be deleted once they have been locked');
    }
}
