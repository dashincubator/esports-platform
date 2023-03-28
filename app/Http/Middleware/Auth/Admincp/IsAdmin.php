<?php

namespace App\Http\Middleware\Auth\Admincp;

use App\User;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class IsAdmin implements Contract
{

    private $responder;

    private $user;


    public function __construct(Responder $responder, User $user)
    {
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        if (!$this->user->isAdmin()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
