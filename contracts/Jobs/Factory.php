<?php declare(strict_types=1);

namespace Contracts\Jobs;

use Contracts\Jobs\Job;

interface Factory
{

    /**
     *  Create Job Class
     *
     *  @see Job Comments
     */
    public function createJob(string $classname, string $method, array $parameters, bool $useLock = false) : Job;
}
