<?php

namespace App\Commands\Ladder\Match\Delete;

use App\Commands\Event\Match\Delete\AbstractCommand;
use App\Commands\Ladder\Match\Report\Delete\Command as DeleteReportCommand;
use App\DataSource\Ladder\Match\Mapper;

class Command extends AbstractCommand
{

    public function __construct(DeleteReportCommand $report, Filter $filter, Mapper $mapper)
    {
        parent::__construct($report, $filter, $mapper);
    }


    protected function run(?int $id, ?int $ladder) : bool
    {
        if ($id) {
            $this->deleteById($id);
        }
        elseif ($ladder) {
            $this->deleteByEvent($ladder);
        }

        return $this->booleanResult();
    }
}
