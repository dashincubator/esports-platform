<?php

namespace App\Bootstrap\Stages\Http;

use App\Organization;
use App\Http\Responders\Html as Responder;
use Contracts\Http\{Request, Response};
use Closure;

class VerifyOrganization
{

    private $organization;

    private $responder;


    public function __construct(Organization $organization, Responder $responder) {
        $this->organization = $organization;
        $this->responder = $responder;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        if ($this->organization->isEmpty()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
