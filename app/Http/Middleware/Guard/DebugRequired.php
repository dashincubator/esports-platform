<?php

namespace App\Http\Middleware\Guard;

use App\User;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class DebugRequired implements Contract
{

    private $debug;

    private $responder;

    private $user;


    public function __construct(Responder $responder, User $user, bool $debug)
    {
        $this->debug = $debug;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        if (!$this->debug && $this->user->getId() !== 1) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
