<?php

namespace App\Http\Middleware\FindBySlugs;

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
        $attributes->set('game', $game = $this->mapper->findBySlugs(
            $attributes->get('route.variables.game'),
            $attributes->get('route.variables.platform')
        ));

        if ($game->isEmpty()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
