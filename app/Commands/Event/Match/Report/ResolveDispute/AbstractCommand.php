<?php

namespace App\Commands\Event\Match\Report\ResolveDispute;

use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\Match\AbstractMapper as MatchMapper;
use App\DataSource\Event\Match\Report\AbstractMapper as MatchReportMapper;

abstract class AbstractCommand extends AbstractParent
{

    private $mapper;


    public function __construct(Filter $filter, MatchMapper $match, MatchReportMapper $report)
    {
        $this->filter = $filter;
        $this->mapper = compact('match', 'report');
    }


    protected function run(int $match, array $placements, int $user) : bool
    {
        $reports = $this->mapper['report']->findByMatch($match);

        if ($reports->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            // Validate Match Status
            $match = $this->mapper['match']->findById($reports->getMatch());

            if (!$match->isReportable()) {
                $this->filter->writeCannotReportMessage();
            }

            // Validate Placements
            $placements = array_combine(array_column($placements, 'team'), array_column($placements, 'placement'));

            $ranks = array_unique(array_values($placements));
            $ranks = array_filter($ranks, function($value) use ($reports) {
                return $value <= count($reports) && $value > 0;
            });

            if (count($ranks) !== count($placements)) {
                $this->filter->writeInvalidPlacementsMessage();
            }

            foreach ($reports->getTeamIds() as $team) {
                if (!array_key_exists($team, $placements)) {
                    $this->filter->writeInvalidPlacementsMessage();
                }

                $reports->get('team', $team)->placed($placements[$team], $user);
            }
        }

        if (!$this->filter->hasErrors()) {
            $this->mapper['report']->update(...iterator_to_array($reports));
            $this->mapper['match']->scheduleUpdateJob([
                'id' => $reports->getMatch()
            ]);
        }

        return $this->booleanResult();
    }
}
