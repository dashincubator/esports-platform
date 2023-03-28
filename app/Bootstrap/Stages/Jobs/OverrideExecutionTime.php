<?php

namespace App\Bootstrap\Stages\Jobs;

use Contracts\Bootstrap\Application;
use Closure;

class OverrideExecutionTime
{

    public function handle(Application $app, Closure $next)
    {
        set_time_limit(0);

        return $next($app);
    }
}
