<?php

namespace App\DataSource\Ladder\Team;

use App\DataSource\Event\Team\AbstractRecord;

class Record extends AbstractRecord
{

    protected $earnings = 0;

    protected $glicko2Rating = 0;

    protected $glicko2RatingDeviation = 0;

    protected $glicko2Volatility = 0;

    protected $ladder;

    protected $rank = 0;

    protected $score = 0;

    protected $scoreModifiedAt = 0;
}
