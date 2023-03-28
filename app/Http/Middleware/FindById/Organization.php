<?php

namespace App\Http\Middleware\FindById;

use App\DataSource\Organization\Mapper;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Organization implements Contract
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
        $attributes->set('organization', $organization = $this->mapper->findById($attributes->get('route.variables.id')));

        if ($organization->isEmpty()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
