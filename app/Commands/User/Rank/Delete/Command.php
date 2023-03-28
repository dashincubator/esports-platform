<?php

namespace App\Commands\User\Rank\Delete;

use App\Commands\AbstractCommand;
use App\DataSource\User\Rank\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    private function delete(Entity ...$ranks) : void
    {
        $this->mapper->delete(...$ranks);
    }


    private function deleteById(int $id) : void
    {
        $rank = $this->mapper->findById($id);

        if ($rank->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->delete($rank);
        }
    }


    private function deleteByUser(int $user) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByUser($user))
        );
    }


    protected function run(?int $id, ?int $user) : bool
    {
        if ($id) {
            $this->deleteById($id);
        }
        elseif ($user) {
            $this->deleteByUser($user);
        }

        return $this->booleanResult();
    }
}
