<?php

/**
 *------------------------------------------------------------------------------
 *
 *  "Extend" Base Application Boot Configuration
 *
 */

$app = require realpath(__DIR__ . '/..') . '/app.php';

/**
 *------------------------------------------------------------------------------
 *
 *  Cron List Seperated By Interval
 *
 */

use App\Jobs;

$app['jobs'] = [
    '@always' => [],
    '@1min' => [
        Jobs\Schedule\Game\Api\Match\Update::class,
        Jobs\Schedule\Ladder\Team\Update\Leaderboards::class,
        Jobs\Queue\AddWorker::class
    ],
    '@3min' => [
        Jobs\Queue\ActivateDelayedJobs::class,
        Jobs\Schedule\User\ForgotPassword\DeleteExpired::class
    ],
    '@5min' => [
        Jobs\Schedule\Ladder\Match\Start::class,
        Jobs\Schedule\Ladder\Match\Update::class,
        Jobs\Schedule\Ladder\Team\Update\GameStats::class,
        // TODO: Replace
        Jobs\Commands\App\UpdateCounters::class
    ],
    '@15min' => []
];

/**
 *------------------------------------------------------------------------------
 *
 *  Cron Application Providers
 *
 */

use App\Bootstrap\Providers;

$app['providers'] = array_merge($app['providers'], [
    Providers\Contracts\JobsProvider::class
]);


/**
 *------------------------------------------------------------------------------
 *
 *  Jobs Stages
 *
 */

use App\Bootstrap\Stages;

$app['stages'] = array_merge($app['stages'], [
    Stages\Jobs\OverrideExecutionTime::class,
    Stages\Jobs\Dispatch::class
]);

/**
 *------------------------------------------------------------------------------
 *
 *  Return Cron Application Boot Configuration
 *
 */

return $app;
