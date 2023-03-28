<?php

namespace App\Commands\Ladder\Match\Report\ResolveDispute;

use App\Commands\Event\Match\Report\ResolveDispute\{AbstractCommand, Filter};
use App\DataSource\Ladder\Match\Mapper as MatchMapper;
use App\DataSource\Ladder\Match\Report\Mapper as MatchReportMapper;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, MatchMapper $match, MatchReportMapper $report)
    {
        parent::__construct($filter, $match, $report);
    }
}
