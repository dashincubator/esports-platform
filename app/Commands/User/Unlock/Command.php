<?php

namespace App\Commands\User\Unlock;

use App\Commands\AbstractCommand;
use App\DataSource\User\Mapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(int $id) : bool
    {
        $user = $this->mapper->findById($id);

        if ($user->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $user->unlock();
            $this->mapper['user']->update($user);
        }

        return $this->booleanResult();
    }
}
