<?php

namespace App\Commands\Event\Team\Member\Delete;

use App\Commands\AbstractCommand as AbstractParent;
use App\DataSource\Event\Team\Member\{AbstractEntity as Entity, AbstractMapper as Mapper};

abstract class AbstractCommand extends AbstractParent
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    private function delete(Entity ...$members) : void
    {
        $this->mapper->delete(...$members);
    }


    private function deleteById(int $id) : void
    {
        $member = $this->mapper->findById($id);

        if ($member->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->delete($member);
        }
    }


    private function deleteByTeam(int $team) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByTeam($team))
        );
    }


    private function deleteByTeams(array $teams) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByTeams(...$teams))
        );
    }


    protected function run(?int $id, ?int $team, ?array $teams) : bool
    {
        if ($id) {
            $this->deleteById($id);
        }
        elseif ($team) {
            $this->deleteByTeam($team);
        }
        elseif ($teams) {
            $this->deleteByTeams($teams);
        }

        return $this->booleanResult();
    }
}
