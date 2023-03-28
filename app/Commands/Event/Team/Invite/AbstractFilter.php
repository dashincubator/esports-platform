<?php

namespace App\Commands\Event\Team\Invite;

use App\Commands\AbstractFilter as AbstractParent;

abstract class AbstractFilter extends AbstractParent
{

    protected function getRules(array $data = []) : array
    {
        return [
            'team' => [
                'int' => $this->templates->invalid('team'),
                'required' => $this->templates->required('team')
            ],
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return "Team invite {$action} successfully!";
    }


    public function writeRosterFullMessage(string $action = '') : void
    {
        $this->error("Team invite cannot be {$action}, team roster is already full");
    }


    public function writeRosterLockedMessage(string $action = '') : void
    {
        $this->error("Team invite cannot be {$action} once a team has been locked");
    }
}
