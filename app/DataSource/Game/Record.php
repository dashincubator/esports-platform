<?php

namespace App\DataSource\Game;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $account = '';

    protected $banner = '';

    protected $card = '';

    protected $id;

    protected $name;

    protected $platform;

    protected $slug;

    protected $totalActiveLadders = 0;

    protected $totalActiveLeagues = 0;

    protected $totalMatchesPlayed = 0;

    protected $totalActiveTournaments = 0;

    protected $totalPrizesPaid = 0;

    protected $totalWagered = 0;

    protected $view = '';


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
