<?php

namespace App\Bootstrap\Providers\View\Extensions;

use App\Bootstrap\Providers\AbstractProvider;
use App\View\Extensions\Leaderboard;

class LeaderboardProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->bind(Leaderboard::class, null, [$this->config->get('game.rating.period')]);
    }
}
