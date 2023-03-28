<?php

namespace App\Http\Middleware\Auth\Ladder\Match;

use App\DataSource\Ladder\Match\Report\Mapper;
use App\User;
use App\Http\Responders\Html as Responder;
use App\Services\Auth\Ladder\Team as Auth;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Report implements Contract
{

    private $auth;

    private $mapper;

    private $responder;

    private $user;


    public function __construct(Auth $auth, Mapper $mapper, Responder $responder, User $user)
    {
        $this->auth = $auth;
        $this->mapper = $mapper;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $report = $this->mapper->findById($request->getAttributes()->get('route.variables.report'));

        if ($report->isEmpty() || !$this->auth->matches($report->getTeam(), $this->user->getId())) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
