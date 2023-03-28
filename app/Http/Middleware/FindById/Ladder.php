<?php

namespace App\Http\Middleware\FindById;

use App\DataSource\Ladder\Mapper;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Ladder implements Contract
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
        $attributes->set('ladder', $ladder = $this->mapper->findById($attributes->get('route.variables.id')));

        if ($ladder->isEmpty()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
