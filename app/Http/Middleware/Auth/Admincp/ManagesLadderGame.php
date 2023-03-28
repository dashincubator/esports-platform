<?php

namespace App\Http\Middleware\Auth\Admincp;

use App\User;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class ManagesLadderGame implements Contract
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
        if (!in_array(
            $request->getAttributes()->get('ladder')->getGame(),
            $this->user->getAdminPosition()->getGames()
        )) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
