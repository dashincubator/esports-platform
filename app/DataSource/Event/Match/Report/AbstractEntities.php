<?php

namespace App\DataSource\Event\Match\Report;

use App\DataSource\AbstractEntities as AbstractParent;

abstract class AbstractEntities extends AbstractParent
{

    public function allTeamsReported(int $teamsPerMatch) : bool
    {
        return false;
    }


    public function getMatch() : int
    {
        return $this->entities[0]->getMatch();
    }


    public function getWinningTeamId() : int
    {
        foreach ($this as $report) {
            if ($report->getPlacement() !== 1) {
                continue;
            }

            return $report->getTeam();
        }

        return 0;
    }


    public function invalidReportFound(int $teamsPerMatch) : bool
    {
        return true;
    }


    public function sortTeamsByPlacement() : array
    {
        $teams = [];

        foreach ($this as $report) {
            $teams[$report->getPlacement()] = $report->getTeam();
        }

        return array_values($teams);
    }
}
