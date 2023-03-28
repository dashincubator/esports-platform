<?php

namespace App\Commands\Ladder\Team\Member\Delete;

use App\Commands\Event\Team\Member\Delete\{AbstractCommand, Filter};
use App\DataSource\Ladder\Team\Member\Mapper;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, Mapper $mapper)
    {
        parent::__construct($filter, $mapper);
    }
}
