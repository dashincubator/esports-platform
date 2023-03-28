<?php

namespace App\DataSource\Ladder\Match\Report;

use App\DataSource\Event\Match\Report\AbstractEntities;

class Entities extends AbstractEntities
{

    public function allTeamsReported(int $teamsPerMatch) : bool
    {
        if (count($this->entities) !== $teamsPerMatch) {
            return false;
        }

        foreach ($this as $report) {
            if ($report->isReported()) {
                continue;
            }

            return false;
        }

        return true;
    }


    public function getTeamIds() : array
    {
        return $this->column('team');
    }


    public function invalidReportFound(int $teamsPerMatch) : bool
    {
        $placement = range(1, $teamsPerMatch);
        $reports = array_unique($this->column('placement'));

        return count(array_diff($placement, $reports)) > 0;
    }


    public function sortRostersByPlacement() : array
    {
        $rosters = [];

        foreach ($this as $report) {
            $rosters[$report->getPlacement()] = $report->getRoster();
        }

        return array_values($rosters);
    }
}
