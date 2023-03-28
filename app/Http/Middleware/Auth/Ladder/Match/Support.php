<?php

namespace App\Http\Middleware\Auth\Ladder\Match;

use App\DataSource\Ladder\Match\Report\Mapper;
use App\User;
use App\Http\Responders\Html as Responder;
use App\Services\Auth\Ladder\Team as Auth;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Support implements Contract
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
        $attributes = $request->getAttributes();

        $match = $attributes->get('match');
        $report = $this->mapper->findById($attributes->get('route.variables.report'));

        if (!$match->isReportable() || $report->isEmpty() || !$this->auth->matches($report->getTeam(), $this->user->getId())) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
