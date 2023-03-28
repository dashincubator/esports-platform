<?php declare(strict_types=1);

namespace Contracts\Jobs;

interface Log
{

    /**
     *  Log Worker Activity
     *
     *  @param array $activity Worker Activity
     */
    public function activity(array $activity) : void;

    
    /**
     *  Delete Completed Worker
     *
     *  @param array $activity Worker Activity
     */
    public function delete(array $activity) : void;
}
