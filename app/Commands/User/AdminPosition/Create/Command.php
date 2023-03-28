<?php

namespace App\Commands\User\AdminPosition\Create;

use App\Commands\AbstractCommand;
use App\DataSource\User\Admin\Position\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $mapper;

    private $organization;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(array $games, string $name, int $organization, array $permissions) : Entity
    {
        $position = $this->mapper->create(compact('name', 'organization'));
        $position->updateGames(...$games);
        $position->updatePermissions(...$permissions);

        if (!$this->filter->hasErrors()){
            $this->filter->writeSuccessMessage();
            $this->mapper->insert($position);

            return $position;
        }

        return $this->mapper->create();
    }
}
