<?php

namespace App\DataSource\Ladder\Gametype;

use App\DataSource\AbstractEntity;

class Entity extends AbstractEntity
{

    protected $guarded = [
        'id'
    ];


    protected function setBestOf(array $bestOf) : array
    {
        return array_unique(array_filter(array_map('trim', $bestOf)));
    }


    protected function setMapsets(array $input) : array
    {
        $mapsets = [];

        foreach ($input as $i) {
            $mapsets[$i['gametype']] = ($i['maps'] ?? []);
        }

        return $mapsets;
    }


    protected function setModifiers(array $modifiers) : array
    {
        return array_unique(array_filter(array_map('trim', $modifiers)));
    }


    protected function setPlayersPerTeam(array $playersPerTeam) : array
    {
        return array_unique(array_filter(array_map('trim', $playersPerTeam)));
    }


    protected function setTeamsPerMatch(array $teamsPerMatch) : array
    {
        return array_unique(array_filter(array_map('trim', $teamsPerMatch)));
    }
}
