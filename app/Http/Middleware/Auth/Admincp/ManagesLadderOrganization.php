<?php

namespace App\Http\Middleware\Auth\Admincp;

use App\Organization;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class ManagesLadderOrganization implements Contract
{

    private $organization;

    private $responder;


    public function __construct(Organization $organization, Responder $responder)
    {
        $this->organization = $organization;
        $this->responder = $responder;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $ladder = $request->getAttributes()->get('ladder');

        if ($ladder->getOrganization() !== $this->organization->getId()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
