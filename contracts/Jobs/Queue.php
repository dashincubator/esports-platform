<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  FIFO ( First In First Out ) Queue
 *
 */

namespace Contracts\Jobs;

interface Queue
{

    /**
     *  Move Delayed Jobs Into Active Job Queue
     *
     *  @param string $active Job Queue Key
     *  @param string $delayed Job Queue Key
     */
    public function activateDelayedJobs(string $active, string $delayed) : void;


    /**
     *  Add New Job To Queue
     *
     *  @see Job Factory
     */
    public function add(string $queue, string $classname, string $method, array $parameters, int $seconds = 0) : bool;


    /**
     *  Clear Entire Queue
     *
     *  @param string $queue Queue Name
     */
    public function clear(string $queue) : void;


    /**
     *  Count Elements In Queue
     *
     *  @param string $queue Queue Name
     *  @return int Total Count
     */
    public function count(string $queue) : int;


    /**
     *  True If Job Requires Lock To Prevent Overlapping
     *
     *  @param Job $job
     *  @return bool
     */
    public function isLocked(Job $job) : bool;


    /**
     *  Create Lock For Job
     *
     *  @param Job $job
     */
    public function lock(Job $job) : void;


    /**
     *  Returns Next Value In Queue, Also Removes From The Queue
     *
     *  @param string $queue Queue Name
     *  @return null|Job
     */
    public function next(string $queue) : ?Job;


    /**
     *  Remove Job Lock
     *
     *  @param Job $job
     */
    public function unlock(Job $job) : void;
}
