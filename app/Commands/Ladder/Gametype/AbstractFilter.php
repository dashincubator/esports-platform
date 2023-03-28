<?php

namespace App\Commands\Ladder\Gametype;

use App\Commands\AbstractFilter as AbstractParent;

abstract class AbstractFilter extends AbstractParent
{

    protected function getRules(array $data = []) : array
    {
        return [
            'bestOf' => [
                'array' => $this->templates->invalid('best of'),
                'min:1' => $this->templates->invalid('best of'),
                'required' => $this->templates->required('best of')
            ],
            'bestOf.*' => [
                'int' => $this->templates->invalid('best of'),
                'required' => $this->templates->required('best of')
            ],
            'game' => [
                'int' => $this->templates->invalid('game'),
                'required' => $this->templates->required('game')
            ],
            'mapsets' => [
                'array' => $this->templates->invalid('mapsets list'),
                'min:1' => $this->templates->invalid('mapsets list'),
                'required' => $this->templates->required('mapsets list')
            ],
            'mapsets.*.gametype' => [
                'required' => $this->templates->required('mapsets list gametype'),
                'string' => $this->templates->string('mapsets list gametype')
            ],
            'mapsets.*.maps' => [
                'array' => $this->templates->invalid('mapsets list maps')
            ],
            'mapsets.*.maps.*' => [
                'string' => $this->templates->string('mapsets list map')
            ],
            'modifiers' => [
                'array' => $this->templates->invalid('modifiers list')
            ],
            'modifiers.*' => [
                'string' => $this->templates->string('modifiers list')
            ],
            'name' => [
                'required' => $this->templates->required('name'),
                'string' => $this->templates->string('name')
            ],
            'playersPerTeam' => [
                'array' => $this->templates->invalid('players per team list'),
                'min:1' => $this->templates->invalid('players per team list'),
                'required' => $this->templates->required('players per team list')
            ],
            'playersPerTeam.*' => [
                'int' => $this->templates->invalid('players per team list'),
                'required' => $this->templates->required('players per team list')
            ],
            'teamsPerMatch' => [
                'array' => $this->templates->invalid('teams per match list'),
                'min:1' => $this->templates->invalid('teams per match list'),
                'required' => $this->templates->required('teams per match list')
            ],
            'teamsPerMatch.*' => [
                'int' => $this->templates->invalid('teams per match list'),
                'required' => $this->templates->required('teams per match list')
            ]
        ];
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return "Gametype {$action} successfully!";
    }
}
