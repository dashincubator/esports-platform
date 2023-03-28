<?php

namespace App\Commands\Ladder\Team\Member\Edit;

use App\Commands\Event\Team\Member\Edit\{AbstractCommand, Filter};
use App\Commands\Ladder\Team\Member\Kick\Command as KickCommand;
use App\Commands\Ladder\Team\Member\Update\Command as UpdateCommand;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, KickCommand $kick, UpdateCommand $update)
    {
        parent::__construct($filter, $kick, $update);
    }
}
