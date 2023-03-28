<?php

namespace App\Commands\Ladder\Gametype\Create;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\Gametype\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(
        array $bestOf,
        int $game,
        array $mapsets,
        ?array $modifiers,
        string $name,
        array $playersPerTeam,
        array $teamsPerMatch
    ) : Entity
    {
        $this->filter->writeSuccessMessage();
        $this->mapper->insert(
            $gametype = $this->mapper->create(compact($this->filter->getFields()))
        );

        return $gametype;
    }
}
