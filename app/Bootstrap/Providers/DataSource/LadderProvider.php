<?php

namespace App\Bootstrap\Providers\DataSource;

use App\DataSource\Ladder\Match\Entity as MatchEntity;
use App\Bootstrap\Providers\AbstractProvider;

class LadderProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerMatchEntityBinding();
    }


    private function registerMatchEntityBinding() : void
    {
        $this->container->bind(MatchEntity::class, null, [$this->config->get('game.match.report.timeout'), $this->config->get('game.match.interval')]);
    }
}
