<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Cron Job
 *
 */

namespace Contracts\App\Jobs;

interface Job
{

    /**
     *  Perform Cron Job Action
     */
    public function handle() : void;
}
