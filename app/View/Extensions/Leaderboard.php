<?php

namespace App\View\Extensions;

use App\View\Extensions\Avg;
use Contracts\Configuration\Configuration;
use Contracts\View\Extensions\Data;

class Leaderboard
{

    private $avg;

    private $ratingPeriod;


    public function __construct(Avg $avg, int $ratingPeriod)
    {
        $this->avg = $avg;
        $this->ratingPeriod = $ratingPeriod;
    }


    public function createTableRowModifiers(Data $row, bool $scoreBased = false) : string
    {
        $modifier = '';
        $row = $this->toTableItemArray($row, $scoreBased);

        if (is_numeric($row['rank'])) {
            if ($row['rank'] === 1) {
                $modifier = 'table-row--border-primary';
            }
            elseif ($row['rank'] <= 50) {
                $modifier = 'table-row--border-gradient-black';
            }
        }

        return $modifier;
    }


    public function toTableItemArray(Data $row, bool $scoreBased = false) : array
    {
        $mp = $row['wins'] + $row['losses'];
        $wp = $this->avg->wins($row);

        $losses = $row['losses'];
        $rank = '-';
        $score = '-';
        $wins = $row['wins'];

        if ($mp > $this->ratingPeriod || $scoreBased) {
            $score = $row['score'];

            if ($score > 0) {
                $rank = $row['rank'];
            }
        }

        return compact('mp', 'wp', 'losses', 'rank', 'score', 'wins');
    }


    public function toTooltipSectionArray(Data $row) : array
    {
        $items = $this->toTableItemArray($row);

        return [
            [
                'svg' => 'match',
                'text' => $row['mp'],
                'title' => 'Matches Played'
            ],
            [
                'svg' => 'win',
                'text' => $row['wins'],
                'title' => 'Wins'
            ],
            [
                'svg' => 'loss',
                'text' => $row['losses'],
                'title' => 'Loss'
            ],
            [
                'svg' => 'score',
                'text' => $row['score'],
                'title' => 'Score'
            ]
        ];
    }
}
