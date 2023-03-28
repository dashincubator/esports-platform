<?php

namespace App\DataSource\Ladder\Match;

use App\DataSource\Event\Match\AbstractRecord;

class Record extends AbstractRecord
{

    protected $bestOf;

    protected $createdAt;

    protected $gametype;

    protected $hosts = [];

    protected $ladder;

    protected $mapset = '';

    protected $modifiers;

    protected $playersPerTeam;

    protected $team;

    protected $teamsPerMatch;

    protected $user;

    protected $wager = 0;

    protected $wagerComplete = 0;


    protected function getCasts() : array
    {
        return [
            'hosts' => 'array',
            'mapset' => 'array',
            'modifiers' => 'array',
            'wagerComplete' => 'bool'
        ];
    }
}
