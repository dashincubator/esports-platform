<?php declare(strict_types=1);

namespace Contracts\Jobs;

interface Worker
{

    /**
     *  Execute Worker
     *
     *  @param string $queue Job Queue Key
     */
    public function execute(string $queue) : void;
}
