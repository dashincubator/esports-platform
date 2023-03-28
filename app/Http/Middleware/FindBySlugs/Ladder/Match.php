<?php

namespace App\Http\Middleware\FindBySlugs\Ladder;

use App\DataSource\Ladder\Match\Mapper;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Match implements Contract
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
        $attributes->set('match', $match = $this->mapper->findById($attributes->get('route.variables.match')));

        if ($match->isEmpty() || $match->getLadder() !== $attributes->get('ladder')->getId()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
