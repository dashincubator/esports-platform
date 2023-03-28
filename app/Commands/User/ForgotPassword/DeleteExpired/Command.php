<?php

namespace App\Commands\User\ForgotPassword\DeleteExpired;

use App\Commands\AbstractCommand;
use App\DataSource\User\ForgotPassword\Mapper;

class Command extends AbstractCommand
{

    private $minutes;

    private $mapper;



    public function __construct(Mapper $mapper, int $minutes)
    {
        $this->mapper = $mapper;
        $this->minutes = $minutes;
    }


    protected function run() : bool
    {
        $this->mapper->delete(...$this->mapper->findExpired($this->minutes));

        return $this->booleanResult();
    }
}
