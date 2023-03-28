<?php

namespace App\Commands\User\ForgotPassword\Delete;

use App\Commands\AbstractCommand;
use App\DataSource\User\ForgotPassword\Mapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(int $user) : bool
    {
        $this->mapper->delete($this->mapper->findByUser($user));

        return $this->booleanResult();
    }
}
