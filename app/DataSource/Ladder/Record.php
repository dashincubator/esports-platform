<?php

namespace App\DataSource\Ladder;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $banner = '';

    protected $card = '';

    protected $endsAt;

    protected $entryFee;

    protected $entryFeePrizes = [];

    protected $firstToScore = 0;

    protected $format = '';

    protected $game;

    protected $gametypes = [];

    protected $id;

    protected $info = [];

    protected $minPlayersPerTeam;

    protected $membershipRequired = false;

    protected $maxPlayersPerTeam;

    protected $name;

    protected $organization = 1;

    protected $prizePool = 0;

    protected $prizes = [];

    protected $prizesAdjusted = false;

    protected $rules = [];

    protected $slug;

    protected $startsAt;

    // TODO: Remove
    protected $status = 0;

    protected $stopLoss = 0;

    protected $totalLockedMembers = 0;

    protected $totalMatchesPlayed = 0;

    protected $totalRankedTeams = 0;

    protected $totalRegisteredTeams = 0;

    protected $totalWagered = 0;

    protected $type;


    protected function getCasts() : array
    {
        return [
            'entryFeePrizes' => 'array',
            'gametypes' => 'array',
            'info' => 'array',
            'membershipRequired' => 'bool',
            'prizes' => 'array',
            'prizesAdjusted' => 'bool',
            'rules' => 'array'
        ];
    }


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
