<?php

namespace App\Commands\Event\Team\Member;

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


    public function writeRosterLockedMessage(string $action = '') : void
    {
        $this->error("Team members cannot {$action} once a team has been locked");
    }
}
