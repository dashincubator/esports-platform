<?php

namespace App\Http\Middleware\FindBySlugs;

use App\DataSource\User\Mapper;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Profile implements Contract
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
        $attributes->set('user', $user = $this->mapper->findBySlug($attributes->get('route.variables.slug')));

        if ($user->isEmpty()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
