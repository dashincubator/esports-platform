<?php declare(strict_types=1);

namespace System\Jobs;

use Contracts\Jobs\{Factory, Job, Queue as Contract};
use Contracts\Redis\Redis as Cache;

class Queue implements Contract
{

    private const LOCK_EXPIRATION = 10;


    private $cache;

    private $factory;

    private $prefix;


    public function __construct(Cache $cache, Factory $factory, string $prefix = 'Jobs:Queue')
    {
        $this->cache = $cache;
        $this->factory = $factory;
        $this->prefix = $prefix;
    }


    public function activateDelayedJobs(string $active, string $delayed) : void
    {
        $jobs = $this->cache->zRangeByScore($this->prefix($delayed), 0, time(), ['withscores' => true]);

        if (!$jobs) {
            return;
        }

        $this->cache->pipeline(function($pipe) use ($active, $delayed, $jobs) {
            foreach ($jobs as $job => $timestamp) {
                $pipe->rPush($this->prefix($active), $job);
                $pipe->zRem($this->prefix($delayed), $timestamp);
            }
        });
    }


    public function add(string $queue, string $classname, string $method, array $parameters, int $seconds = 0, bool $useLock = false) : bool
    {
        $job = $this->factory->createJob($classname, $method, $parameters, $useLock);
        $queue = $this->prefix($queue);

        if ($seconds) {
            return (bool) $this->cache->zAdd($queue, (time() + $seconds), $job);
        }

        return (bool) $this->cache->rPush($queue, $job);
    }


    public function clear(string $queue) : void
    {
        $this->cache->delete($this->prefix($queue));
    }


    public function count(string $queue) : int
    {
        return $this->cache->lLen($this->prefix($queue));
    }


    public function isLocked(Job $job) : bool
    {
        return (bool) $this->cache->get($this->prefixLock($job), function() {
            return false;
        });
    }


    public function lock(Job $job) : void
    {
        $this->cache->set($this->prefixLock($job), true, (self::LOCK_EXPIRATION * 60));
    }


    public function next(string $queue) : ?Job
    {
        return ($job = $this->cache->lPop($this->prefix($queue))) ? $job : null;
    }


    private function prefix(string $key) : string
    {
        if ($this->prefix) {
            $key = "{$this->prefix}:{$key}";
        }

        return $key;
    }


    private function prefixLock(Job $job) : string
    {
        return $this->prefix('Lock:' . md5(json_encode($job->toArray())));
    }


    public function unlock(Job $job) : void
    {
        $this->cache->delete($this->prefixLock($job));
    }
}
