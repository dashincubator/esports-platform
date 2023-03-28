<?php

namespace App\Http\Middleware\Auth\Ladder\Team;

use App\User;
use App\Http\Responders\Html as Responder;
use App\Services\Auth\Ladder\Team as Auth;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Edit implements Contract
{

    private $auth;

    private $responder;

    private $user;


    public function __construct(Auth $auth, Responder $responder, User $user)
    {
        $this->auth = $auth;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        if (!$this->auth->edit($request->getAttributes()->get('team')->getId(), $this->user->getId())) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
