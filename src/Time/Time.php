<?php declare(strict_types=1);

namespace System\Time;

use Contracts\Time\Time as Contract;
use DateTime;
use DateTimeZone;
use Exception;

class Time implements Contract
{

    public function generateIdentifierListWithTime() : array
    {
        $now = $this->now();
        $timezones = [];

        foreach (timezone_identifiers_list() as $identifier) {
            $time = new DateTime("@{$now}");
            $time->setTimeZone(new DateTimeZone($identifier));

            $timezones[$identifier] = "{$time->format("h:i A")}";
        }

        return $timezones;
    }


    public function now() : int
    {
        return time();
    }


    public function setTimezone(string $identifier) : void
    {
        date_default_timezone_set($identifier);
    }
}
