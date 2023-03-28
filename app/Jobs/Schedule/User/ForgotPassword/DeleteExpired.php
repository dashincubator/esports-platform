<?php

namespace App\Jobs\Schedule\User\ForgotPassword;

use App\DataSource\User\ForgotPassword\Mapper;
use Contracts\App\Jobs\Job as Contract;

class DeleteExpired implements Contract
{

    private $mapper;


    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }


    public function handle() : void
    {
        $this->mapper->scheduleDeleteExpiredJob();
    } 
}
