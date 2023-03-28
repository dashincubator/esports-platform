<?php

namespace App\Http\Middleware\Redirect;

use App\User;
use App\Http\Responders\Redirect as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class IfGuest implements Contract
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
        if ($this->user->isGuest()) {
            return $this->responder->render('account.auth.sign-in');
        }

        return $next($request);
    }
}
