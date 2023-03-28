<?php

namespace App\Http\Middleware\FindBySlugs\Ladder;

use App\DataSource\Ladder\Team\Mapper;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Team implements Contract
{

    private $mapper;

    private $responder;


    public function __construct(Mapper $mapper, Responder $responder)
    {
        $this->mapper = $mapper;
        $this->responder = $responder;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $attributes = $request->getAttributes();
        $attributes->set('team', $team = $this->mapper->findByLadderAndSlug(
            $attributes->get('ladder')->getId(),
            $attributes->get('route.variables.team')
        ));

        if ($team->isEmpty() || !$team->getId()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
