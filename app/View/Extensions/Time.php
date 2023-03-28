<?php

namespace App\View\Extensions;

use Contracts\Time\Time as TimeContract;

class Time
{

    private $supported;

    private $time;


    public function __construct(TimeContract $time, array $supported)
    {
        $this->supported = $supported;
        $this->time = $time;
    }


    public function toBankFormat(int $time) : string
    {
        return date("M j, Y h:i A T", $time);
    }


    public function toCreatedFormat(int $time) : string
    {
        return date("F j, Y", $time);
    }


    public function toFaqFormat(int $time) : string
    {
        return date("M d, Y", $time);
    }


    public function toIdentifierArray() : array
    {
        $list = $this->time->generateIdentifierListWithTime();
        $timezones = [];

        foreach ($this->supported as $country => $zones) {
            $timezones[$country] = [];

            foreach ($zones as $identifier => $name) {
                $timezones[$country][$identifier] = "[{$list[$identifier]}] {$name}";
            }
        }

        return $timezones;
    }


    public function toJoinedFormat(int $time) : string
    {
        return date("F j, Y", $time);
    }


    public function toLadderFormat(int $time) : string
    {
        return date("D M j, h:i A T", $time);
    }


    public function toLegalFormat(int $time) : string
    {
        return date("M d, Y", $time);
    }


    public function toMatchFormat(int $time) : string
    {
        return date("M j, h:i A T", $time);
    }


    public function toMembershipFormat(int $time) : string
    {
        return date("M j, Y h:i A T", $time);
    }


    public function toTicketFormat(int $time) : string
    {
        return date('M j, h:i A T', $time);
    }


    public function toTournamentFormat(int $time) : string
    {
        return date("D M j, h:i A T", $time);
    }
}
