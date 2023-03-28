<?php

namespace App\Commands\Ladder\Team\Invite\DeleteExpired;

use App\Commands\Event\Team\Invite\DeleteExpired\AbstractCommand;
use App\DataSource\Ladder\Team\Member\Mapper;

class Command extends AbstractCommand
{

    public function __construct(Mapper $mapper, int $minutes)
    {
        parent::__construct($mapper, $minutes);
    }
}
