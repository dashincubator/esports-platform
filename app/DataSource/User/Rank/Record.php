<?php

namespace App\DataSource\User\Rank;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $earnings = 0;

    protected $game;

    protected $glicko2Rating = 0;

    protected $glicko2RatingDeviation = 0;

    protected $glicko2Volatility = 0;

    protected $id;

    protected $losses = 0;

    protected $rank = 0;

    protected $score = 0;

    protected $scoreModifiedAt = 0;

    protected $user;

    protected $wins = 0;


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
