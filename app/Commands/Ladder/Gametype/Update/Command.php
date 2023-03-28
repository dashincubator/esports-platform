<?php

namespace App\Commands\Ladder\Gametype\Update;

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
        int $id,
        array $mapsets,
        ?array $modifiers,
        string $name,
        array $playersPerTeam,
        array $teamsPerMatch
    ) : Entity
    {
        $gametype = $this->mapper->findById($id);

        if ($gametype->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $gametype->fill(compact($this->filter->getFields(['id'])));
        }

        if (!$this->filter->hasErrors()) {
            $this->filter->writeSuccessMessage();
            $this->mapper->update($gametype);
        }

        return $gametype;
    }
}
