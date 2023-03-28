<?php

namespace App\Commands\Ladder\Match\Create;

use App\Commands\Ladder\Match\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'bestOf' => [
                'int' => $this->templates->invalid('best of'),
                'required' => $this->templates->required('best of')
            ],
            'gametype' => [
                'int' => $this->templates->invalid('gametype'),
                'required' => $this->templates->required('gametype')
            ],
            'ladder' => [
                'int' => $this->templates->invalid('ladder'),
                'required' => $this->templates->required('ladder')
            ],
            'modifiers' => [
                'array' => $this->templates->invalid('modifiers')
            ],
            'modifiers.*' => [
                'string' => $this->templates->string('modifiers')
            ],
            'roster' => [
                'array' => $this->templates->invalid('roster'),
                'required' => $this->templates->required('roster')
            ],
            'roster.*' => [
                'int' => $this->templates->invalid('roster'),
                'required' => $this->templates->required('roster')
            ],
            'team' => [
                'int' => $this->templates->invalid('team'),
                'required' => $this->templates->required('team')
            ],
            'teamsPerMatch' => [
                'int' => $this->templates->invalid('teams per match'),
                'required' => $this->templates->required('teams per match')
            ],
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ],
            'wager' => [
                'numeric' => $this->templates->invalid('wager')
            ]
        ];
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('created');
    }


    public function writeInvalidBestOfMessage() : void
    {
        $this->error($this->templates->invalid('best of'));
    }


    public function writeInvalidPlayersPerTeamMessage(string $action = '') : void
    {
        parent::writeInvalidPlayersPerTeamMessage('create');
    }


    public function writeInvalidTeamsPerMatchMessage() : void
    {
        $this->error($this->templates->invalid('teams per match'));
    }


    public function writeLockTeamMessage(string $action = '') : void
    {
        parent::writeLockTeamMessage('creating');
    }


    public function writeReportActiveMatchesMessage() : void
    {
        parent::writeReportActiveMatchesMessage('creating');
    }
}
