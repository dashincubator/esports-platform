<?php

namespace App\Commands\Event\Match\Report\Delete;

use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\Match\Report\{AbstractEntity as Entity, AbstractMapper as Mapper};

abstract class AbstractCommand extends AbstractParent
{

    protected $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    private function delete(Entity ...$reports) : void
    {
        $this->mapper->delete(...$reports);
    }


    private function deleteById(int $id) : void
    {
        $match = $this->mapper->findById($id);

        if ($match->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->delete($match);
        }
    }


    private function deleteByMatch(int $match) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByMatch($match))
        );
    }


    private function deleteByMatches(array $matches) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByMatches(...$matches))
        );
    }


    protected function run(?int $id, ?int $match, ?array $matches) : bool
    {
        if ($id) {
            $this->deleteById($id);
        }
        elseif ($match) {
            $this->deleteByMatch($match);
        }
        elseif ($matches) {
            $this->deleteByMatches($matches);
        }

        return $this->booleanResult();
    }
}
