<?php

namespace App\Commands\User\AdminPosition\Update;

use App\Commands\AbstractCommand;
use App\DataSource\User\Admin\Position\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(array $games, int $id, string $name, array $permissions) : Entity
    {
        $position = $this->mapper->findById($id);

        if ($position->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $position->fill(compact('name'));
            $position->updateGames(...$games);

            if (count($permissions)) {
                $position->updatePermissions(...$permissions);
            }
        }

        if (!$this->filter->hasErrors()){
            $this->filter->writeSuccessMessage();
            $this->mapper->update($position);

            return $position;
        }

        return $this->mapper->create();
    }
}
