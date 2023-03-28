<?php

namespace App\Services\Api\ModernWarfare;

use App\DataSource\Game\Api\Match\Entities as GameApiMatchEntities;

class Calculator
{

    private $multiplier = [
        'wins'  => 5
    ];


    public function kills(GameApiMatchEntities $matches) : int
    {
        $matches = $matches->filterWarzone();
        $score = 0;

        foreach ($matches as $match) {
            $score += $match->getData()['playerStats']['kills'] ?? 0;
        }
 
        return $score;
    }


    public function killsWins(GameApiMatchEntities $matches, int $members) : int
    {
        $matches = $matches->filterWarzone();
        $score = 0;

        foreach ($matches as $match) {
            $score += $match->getData()['playerStats']['kills'] ?? 0;

            if (($match->getData()['playerStats']['teamPlacement'] ?? 0) === 1) {
                $score += ceil(($this->multiplier['wins'] / $members));
            }
        }

        return $score;
    }


    public function wins(GameApiMatchEntities $matches) : int
    {
        $matches = $matches->filterWarzone();
        $score = 0;

        foreach ($matches as $match) {
            if (($match->getData()['playerStats']['teamPlacement'] ?? 0) !== 1) {
                continue;
            }

            $score += 1;
        }

        return $score;
    }
}
