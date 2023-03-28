<?php

namespace App\Commands\User\SignOut;

use App\Commands\AbstractCommand;
use App\Services\Auth\User\SessionDriver as Driver;

class Command extends AbstractCommand
{

    private $driver;


    public function __construct(Driver $driver, Filter $filter)
    {
        $this->driver = $driver;
        $this->filter = $filter;
    }


    protected function run() : bool
    {
        $this->driver->signout();

        return true;
    }
}
