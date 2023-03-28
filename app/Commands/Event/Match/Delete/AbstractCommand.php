<?php

namespace App\Commands\Event\Match\Delete;

use App\Commands\AbstractCommand as AbstractParent;
use App\Commands\Event\Match\Report\Delete\AbstractCommand as DeleteReportCommand;
use App\DataSource\Event\Match\{AbstractEntity as Entity, AbstractMapper as Mapper};

abstract class AbstractCommand extends AbstractParent
{

    private $command;

    private $mapper;


    public function __construct(DeleteReportCommand $report, AbstractFilter $filter, Mapper $mapper)
    {
        $this->command = compact('report');
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    private function delete(Entity ...$matches) : void
    {
        $this->mapper->delete(...$matches);

        $ids = [];

        foreach ($matches as $match) {
            $ids[] = $match->getId();
        }

        $this->delegate($this->command['report'], [
            'matches' => $ids
        ]);
    }


    protected function deleteByEvent(int $event) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByEvent($event))
        );
    }


    protected function deleteById(int $id) : void
    {
        $match = $this->mapper->findById($id);

        if ($match->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->delete($match);
        }
    }
}
