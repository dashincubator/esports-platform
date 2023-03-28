<?php

namespace App\Commands\Ladder\Team\Invite\FindBy;

use App\Commands\Event\Team\Invite\FindBy\{AbstractCommand, Filter};
use App\Commands\Ladder\Team\Invite\Create\Command as CreateCommand;
use App\Commands\User\Find\Command as FindCommand;

class Command extends AbstractCommand
{

    public function __construct(CreateCommand $create, Filter $filter, FindCommand $find)
    {
        parent::__construct($create, $filter, $find);
    }
}
