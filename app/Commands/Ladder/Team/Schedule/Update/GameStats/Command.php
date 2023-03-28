<?php

namespace App\Commands\Ladder\Team\Schedule\Update\GameStats;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\Mapper as LadderMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;

class Command extends AbstractCommand
{

    private const GRACE_PERIOD = 60;

    private $mapper;


    public function __construct(LadderMapper $ladder, TeamMapper $team)
    {
        $this->mapper = compact('ladder', 'team');
    }


    public function run() : void
    {
        $ladders = $this->mapper['ladder']->findAll()->filter(function($ladder) {
            if (!$ladder->isOpen() && !$ladder->isApiGracePeriodOpen()) {
                return false;
            }

            if ($ladder->useDefaultMatchfinder()) {
                return false;
            }

            return true;
        });

        foreach ($ladders as $ladder) {
            $this->mapper['team']->scheduleUpdateGameStatsJob([
                'ladder' => $ladder->getId()
            ]);
        }
    }
}
