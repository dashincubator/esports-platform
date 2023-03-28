<?php

namespace App\Commands\Ladder\Team\Member\Update;

use App\Commands\Event\Team\Member\Update\{AbstractCommand, Filter};
use App\DataSource\Ladder\Team\Member\Mapper;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, Mapper $mapper)
    {
        parent::__construct($filter, $mapper);
    }
}
