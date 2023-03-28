<?php

namespace App\Commands\Ladder\Match\Report\Delete;

use App\Commands\Event\Match\Report\Delete\{AbstractCommand, Filter};
use App\DataSource\Ladder\Match\Report\Mapper;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, Mapper $mapper)
    {
        parent::__construct($filter, $mapper);
    }
}
