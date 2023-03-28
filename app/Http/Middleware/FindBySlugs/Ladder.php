<?php

namespace App\Http\Middleware\FindBySlugs;

use App\Organization;
use App\DataSource\Ladder\Mapper;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class Ladder implements Contract
{

    private $mapper;

    private $organization;

    private $responder;


    public function __construct(Mapper $mapper, Organization $organization, Responder $responder)
    {
        $this->mapper = $mapper;
        $this->organization = $organization;
        $this->responder = $responder;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $attributes = $request->getAttributes();
        $attributes->set('ladder', $ladder = $this->mapper->findByGameOrganizationAndSlug(
            $attributes->get('game')->getId(),
            $this->organization->getId(),
            $attributes->get('route.variables.ladder')
        ));

        if ($ladder->isEmpty()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
