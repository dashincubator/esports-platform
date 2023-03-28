<?php

namespace App\Services\Match;

use App\DataSource\Ladder\Team\Entities as LadderTeamEntities;
use App\DataSource\AbstractEntities;

class SortHost
{

    private const TEXT = "Team with the most wins hosts this match; If it is a tie the team with the higher %s hosts this match.";


    public function ladder(LadderTeamEntities $teams, int $bestOf, int $teamsPerMatch)
    {
        $scores = [];
        $teamids = [];

        // Sort By Scores
        foreach ($teams as $team) {
            $scores[] = $team->getScore();
        }

        ksort($scores);

        foreach ($scores as $score) {
            $teamids = array_merge($teamids, $teams->filter(function($team) use ($score) {
                return $team->getScore() === $score;
            })->column('id'));
        }

        return $this->sort($teams, $bestOf, 'score', $teamids, $teamsPerMatch);
    }


    private function sort(AbstractEntities $teams, int $bestOf, string $sortedBy, array $teamids, int $teamsPerMatch) : array
    {
        $hosts = [];
        $keys = range(0, $bestOf - 1);

        foreach ($keys as $key) {
            $id = $teamids[$key] ?? null;
            $name = null;

            if (!is_null($id)) {
                $name = $teams->get('id', $id)->getName();
            }

            $hosts[] = is_null($name) ? sprintf(self::TEXT, $sortedBy) : $name;
        }

        return $hosts;
    }
}
