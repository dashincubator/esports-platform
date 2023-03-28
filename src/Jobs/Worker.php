<?php declare(strict_types=1);

namespace System\Jobs;

use Contracts\Container\Container;
use Contracts\Jobs\{Log, Queue, Worker as Contract};
use Exception;

class Worker implements Contract
{

    private $container;

    private $counter = 0;

    private $createdAt;

    private $expireAt;

    private $jobs = [];

    private $log;

    private $queue;


    public function __construct(Container $container, Log $log, Queue $queue, int $expireAfter)
    {
        $this->container = $container;
        $this->createdAt = time();
        $this->expireAt = (time() + $expireAfter);
        $this->log = $log;
        $this->queue = $queue;
    }


    private function continue() : bool
    {
        if ($this->expireAt <= time()) {
            return false;
        }

        return true;
    }


    public function execute(string $queue) : void
    {
        try {
            while ($this->continue() && ($job = $this->queue->next($queue))) {
                if ($job->useLock()) {
                    if ($this->queue->isLocked($job)) {
                        continue;
                    }

                    $this->queue->lock($job);
                }

                $this->container->resolve($job->getClassName())->{$job->getMethod()}($job->getParameters());
                $this->counter++;

                $this->jobs[] = "{$job->getClassName()}:{$job->getMethod()}";

                if ($job->useLock()) {
                    $this->queue->unlock($job);
                }

                $this->log($queue);
            }
        }
        catch (Exception $e) {
            if ($job && $job->useLock()) {
                $this->queue->unlock($job);
            }
        }

        $this->log($queue, false);

        // Let Exception Handler Take Over
        if ($e ?? null) {
            throw $e;
        }
    }


    private function log(string $queue, bool $updating = true) : void
    {
        $this->log->{$updating ? 'activity' : 'delete'}([
            'closedAt' => ($updating ? 0 : time()),
            'createdAt' => $this->createdAt,
            'expiredAt' => $this->expireAt,
            'queue' => $queue,
            'jobsProcessed' => $this->jobs,
            'totalJobsProcessed' => $this->counter,
            'updatedAt' => time()
        ]);
    }
}
