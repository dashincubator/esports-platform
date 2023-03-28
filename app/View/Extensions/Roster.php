<?php

namespace App\View\Extensions;

use App\View\Extensions\{Avg, Ordinal};
use Contracts\View\Extensions\Data;

class Roster
{

    private $avg;

    private $leaderboard;

    private $ordinal;


    public function __construct(Avg $avg, Leaderboard $leaderboard, Ordinal $ordinal)
    {
        $this->avg = $avg;
        $this->leaderboard = $leaderboard;
        $this->ordinal = $ordinal;
    }


    public function toTableItemArray(Data $row) : array
    {
        return $this->leaderboard->toTableItemArray($row);
    }


    public function toTooltipSectionArray(Data $row) : array
    {
        $items = $this->toTableItemArray($row);

        return [
            [
                'svg' => 'leaderboard',
                'text' => $items['rank'] . (is_numeric($items['rank']) ? ($this->ordinal)((int) $items['rank']) : ''),
                'title' => 'Arena Rank'
            ],
            [
                'svg' => 'win',
                'text' => $items['wins'],
                'title' => 'Wins'
            ],
            [
                'svg' => 'loss',
                'text' => $items['losses'],
                'title' => 'Loss'
            ],
            [
                'svg' => 'score',
                'text' => $items['score'],
                'title' => 'Score'
            ]
        ];
    }
}
