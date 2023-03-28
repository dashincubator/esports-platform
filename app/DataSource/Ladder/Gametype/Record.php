<?php

namespace App\DataSource\Ladder\Gametype;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $bestOf;

    protected $game;

    protected $id;

    protected $name;

    // ["Blackout" => []]
    protected $mapsets;

    // ["sadsadsa"]
    protected $modifiers = [];

    protected $playersPerTeam;

    protected $teamsPerMatch;


    protected function getCasts() : array
    {
        return [
            'bestOf' => 'array',
            'mapsets' => 'array',
            'modifiers' => 'array',
            'playersPerTeam' => 'array',
            'teamsPerMatch' => 'array'
        ];
    }


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
