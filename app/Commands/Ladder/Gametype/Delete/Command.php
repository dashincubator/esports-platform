<?php

namespace App\Commands\Ladder\Gametype\Delete;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\Gametype\{Entity as GametypeEntity, Mapper as GametypeMapper};
use App\DataSource\Ladder\Match\Mapper as MatchMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, GametypeMapper $gametype, MatchMapper $match)
    {
        $this->filter = $filter;
        $this->mapper = compact('gametype', 'match');
    }


    protected function run(int $id) : bool
    {
        $gametype = $this->mapper['gametype']->findById($id);

        if ($gametype->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif ($this->mapper['match']->countByGametype($gametype->getId())) {
            $this->filter->writeGametypeInUseMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->filter->writeSuccessMessage();
            $this->mapper['gametype']->delete($gametype);
        }

        return $this->booleanResult();
    }
}
