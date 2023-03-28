<?php

namespace App\Http\Middleware\FindBySlugs;

use App\DataSource\Game\Platform\Mapper;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Platform implements Contract
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
        $attributes->set('platform', $platform = $this->mapper->findBySlugs(
            $attributes->get('route.variables.platform')
        ));

        if ($platform->isEmpty()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
