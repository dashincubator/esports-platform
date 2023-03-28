<?php

namespace App\Http\Middleware\FindById;

use App\DataSource\Ladder\Gametype\Mapper;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class LadderGametype implements Contract
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
        $attributes->set('gametype', $gametype = $this->mapper->findById($attributes->get('route.variables.id')));

        if ($gametype->isEmpty()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
