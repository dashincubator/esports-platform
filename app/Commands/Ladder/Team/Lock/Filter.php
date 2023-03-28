<?php

namespace App\Commands\Ladder\Team\Lock;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('team'),
                'required' => $this->templates->required('team')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Team locked successfully!';
    }


    public function writeInvalidRosterSizeMessage(int $min, int $max) : void
    {
        $this->error("Rosters must have a minimum of {$min} players and cannot exceed {$max} players");
    }


    public function writeTeamAlreadyLockedMessage() : void
    {
        $this->error('Your team is already locked');
    }


    public function writeLadderDoesNotRequireTeamLockMessage() : void
    {
        $this->error('Team lock is not required for this event');
    }
}
