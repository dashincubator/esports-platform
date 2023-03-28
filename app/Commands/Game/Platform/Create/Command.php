<?php

namespace App\Commands\Game\Platform\Create;

use App\Commands\AbstractCommand;
use App\DataSource\Game\Platform\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(?string $account, string $name, ?string $slug, ?string $view) : Entity
    {
        $platform = $this->mapper->create(compact($this->filter->getFields()));

        if (!$this->mapper->isUniqueSlug($platform->getSlug())) {
            $this->filter->writeNameUnavailableMessage();
        }

        if (!$this->filter->hasErrors()){
            $this->filter->writeSuccessMessage();
            $this->mapper->insert($platform);

            return $platform;
        }

        return $this->mapper->create();
    }
}
