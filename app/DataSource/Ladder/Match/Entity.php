<?php

namespace App\DataSource\Ladder\Match;

use App\DataSource\Event\Match\AbstractEntity;

class Entity extends AbstractEntity
{

    protected const STATUS_ACTIVE = 2;

    protected const STATUS_COMPLETE = 3;

    protected const STATUS_DISPUTE = 4;

    protected const STATUS_MATCHFINDER = 0;

    protected const STATUS_UPCOMING = 1;


    private $reportTimeout;

    private $startTimeInterval;


    public function __construct(Record $record, int $reportTimeout, int $startTimeInterval)
    {
        parent::__construct($record);

        $this->reportTimeout = $reportTimeout;
        $this->startTimeInterval = $startTimeInterval;
    }


    private function calculateStartTime() : int
    {
        $interval = 60 * $this->startTimeInterval;

        return (round(time() / $interval) * $interval) + $interval;
    }


    public function complete(int $winningTeam = 0) : void
    {
        $this->set('status', self::STATUS_COMPLETE);
        $this->set('winningTeam', $winningTeam);
    }


    public function dispute() : void
    {
        $this->set('status', self::STATUS_DISPUTE);
    }


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function inMatchFinder() : bool
    {
        return in_array($this->get('status'), [self::STATUS_UPCOMING, self::STATUS_MATCHFINDER]);
    }


    public function isActive() : bool
    {
        return $this->get('status') === self::STATUS_ACTIVE;
    }


    public function isComplete() : bool
    {
        return $this->get('status') === self::STATUS_COMPLETE;
    }


    public function isDisputed() : bool
    {
        return $this->get('status') === self::STATUS_DISPUTE;
    }


    public function isReportable() : bool
    {
        if (!parent::isReportable()) {
            return false;
        }

        // Ladder Matches Have A x Minute Report Time Delay
        if (($this->get('startedAt') + ($this->reportTimeout * 60)) > time()) {
            return false;
        }

        return true;
    }


    public function isUpcoming() : bool
    {
        return $this->get('status') === self::STATUS_UPCOMING;
    }


    public function isWager() : bool
    {
        return $this->get('wager') > 0;
    }


    public function isWagerComplete() : bool
    {
        return $this->get('wager') > 0 && $this->get('wagerComplete');
    }


    public function start() : void
    {
        $this->set('startedAt', time());
        $this->set('status', self::STATUS_ACTIVE);

        // Ladder Matches Have A x Minute Start Time Delay
        $this->set('startedAt', $this->calculateStartTime());
    }


    public function toArray() : array
    {
        $data = parent::toArray();
        $data['reportableAt'] = ($this->get('startedAt') + ($this->reportTimeout * 60));

        return $data;
    }


    // Start Time Is Set But Teams Can Still Join Until The Match Is Started Or
    // The Match Is Full ( Meets Team Count Requirement )
    // - When Set A Countdown Is Displayed To Show Time Left To Join The Match
    //   In MatchFinder
    public function upcoming() : void
    {
        $this->set('startedAt', $this->calculateStartTime());
        $this->set('status', self::STATUS_UPCOMING);
    }


    public function wagerComplete() : void
    {
        $this->set('wagerComplete', true);
    }
}
