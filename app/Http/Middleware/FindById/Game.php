<?php

namespace App\Http\Middleware\FindById;

use App\DataSource\Game\Mapper;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Game implements Contract
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
        $attributes->set('game', $game = $this->mapper->findById($attributes->get('route.variables.id')));

        if ($game->isEmpty()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
