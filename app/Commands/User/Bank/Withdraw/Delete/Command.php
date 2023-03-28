<?php

namespace App\Commands\User\Bank\Withdraw\Delete;

use App\Commands\AbstractCommand;
use App\DataSource\User\Bank\Withdraw\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    private function delete(Entity ...$transactions)
    {
        $this->mapper->delete(...$transactions);
    }


    private function deleteById(int $id) : void
    {
        $withdraw = $this->mapper->findById($id);

        if ($withdraw->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->delete($withdraw);
        }
    }


    private function deleteByUser(int $user) : void
    {
        while(!($withdraws = $this->mapper->findByUser($user))->isEmpty()) {
            $this->delete(...iterator_to_array($withdraws));
        }
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
