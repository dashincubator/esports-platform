<?php

namespace App\Http\Middleware\User;

use App\User;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};
use Contracts\Time\Time;

class SetTimezone implements Contract
{

    private $time;

    private $user;


    public function __construct(Time $time, User $user)
    {
        $this->time = $time;
        $this->user = $user;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        if (!$this->user->isGuest()) {
            $this->time->setTimezone($this->user->getTimezone());
        }

        return $next($request);
    }
}
