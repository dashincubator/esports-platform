<?php

namespace App\Commands\Ladder\Match\Accept;

use App\Commands\Ladder\Match\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('match'),
                'required' => $this->templates->required('match')
            ],
            'roster' => [
                'array' => $this->templates->invalid('roster list'),
                'required' => $this->templates->required('roster list')
            ],
            'roster.*' => [
                'int' => $this->templates->invalid('roster list'),
                'required' => $this->templates->required('roster list')
            ],
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
        return parent::getSuccessMessage('accepted');
    }


    public function writeCantAcceptOwnMatchMessage() : void
    {
        $this->error('You cannot accept your own match');
    }


    public function writeInvalidPlayersPerTeamMessage(string $action = '') : void
    {
        parent::writeInvalidPlayersPerTeamMessage('accept');
    }


    public function writeLockTeamMessage(string $action = '') : void
    {
        parent::writeLockTeamMessage('accepting');
    }


    public function writeReportActiveMatchesMessage(string $action = '') : void
    {
        parent::writeReportActiveMatchesMessage('accepting');
    }
}
