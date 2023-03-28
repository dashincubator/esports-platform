<?php

namespace App\Commands\Ladder\Match;

use App\Commands\AbstractFilter as AbstractParent;

abstract class AbstractFilter extends AbstractParent
{

    protected function getSuccessMessage(string $action = '') : string
    {
        return "Match {$action} successfully!";
    }


    public function writeActiveMatchMessage() : void
    {
        $this->error('Match has already been accepted by another team');
    }


    public function writeInvalidPlayersPerTeamMessage(string $action = '') : void
    {
        $this->error("You did not select enough members to {$action} this match");
    }


    public function writeLadderClosedMessage() : void
    {
        $this->error('Ladder has been closed');
    }


    public function writeLadderStartsMessage(string $action, int $time) : void 
    {
        $this->error("{$action} ladder play starts " . date("M j, Y h:i A T", $time));
    }


    public function writeLockTeamMessage(string $action = '') : void
    {
        $this->error("Your team must be locked before {$action} a match. Visit your team profile for more info");
    }


    public function writeReportActiveMatchesMessage(string $action = '') : void
    {
        $this->error("All active matches must be reported before {$action} a new match");
    }


    public function writeStopLossMessage() : void
    {
        $this->error('Your team has reached the maximum amount of losses allowed for this event');
    }


    public function writeUnauthorizedMessage() : void
    {
        $this->error('You are not authorized to manage matches for this team');
    }
}
