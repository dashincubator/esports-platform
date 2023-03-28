<?php

namespace App\Commands\Ladder\Match\Refund;

use App\Commands\AbstractCommand;
use App\Commands\User\Bank\Transaction\Refund\Command;
use App\DataSource\User\Bank\Transaction\Mapper;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(Command $command, Filter $filter, Mapper $mapper)
    {
        $this->command = $command;
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(int $ladderMatch) : bool
    {
        $transactions = $this->mapper->findByLadderMatch($ladderMatch);

        if (!$transactions->isEmpty()) {
            $this->delegate($this->command, ['ids' => $transactions->column('id')]);
        }

        return $this->booleanResult();
    }
}
