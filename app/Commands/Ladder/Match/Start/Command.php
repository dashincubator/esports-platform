<?php

namespace App\Commands\Ladder\Match\Start;

use App\DataSource\Ladder\Match\Mapper;
use App\Commands\AbstractCommand;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(int $id) : bool
    {
        $match = $this->mapper->findById($id);

        if ($match->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif (!$match->isUpcoming()) {
            $this->filter->writeInvalidMatchErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $match->start();
            $this->mapper->update($match);
        }

        return $this->booleanResult();
    }
}
