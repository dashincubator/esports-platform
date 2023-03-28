<?php

namespace App\Commands\User\SignIn;

use App\Commands\AbstractCommand;
use App\DataSource\User\Mapper;
use App\Services\Auth\User\SessionDriver as Driver;

class Command extends AbstractCommand
{

    private $driver;

    private $mapper;


    public function __construct(Driver $driver, Filter $filter, Mapper $mapper)
    {
        $this->driver = $driver;
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(string $identifier, string $password) : bool
    {
        $user = $this->mapper->findByIdentifier($identifier);

        if ($user->isEmpty() || !$user->isValidPassword($password)) {
            $this->filter->writeInvalidCredentialsMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->driver->signin($user);
        }

        return $this->booleanResult();
    }
}
