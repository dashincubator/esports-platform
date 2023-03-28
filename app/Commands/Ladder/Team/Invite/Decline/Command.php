<?php

namespace App\Commands\Ladder\Team\Invite\Decline;

use App\Commands\Event\Team\Invite\Decline\{AbstractCommand, Filter};
use App\DataSource\Ladder\Team\Member\Mapper;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, Mapper $mapper)
    {
        parent::__construct($filter, $mapper);
    }
}
