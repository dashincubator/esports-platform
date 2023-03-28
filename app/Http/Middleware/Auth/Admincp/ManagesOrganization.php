<?php

namespace App\Http\Middleware\Auth\Admincp;

use App\Organization;
use App\User;
use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class ManagesOrganization implements Contract
{

    private $organization;

    private $responder;

    private $user;


    public function __construct(Organization $organization, Responder $responder, User $user)
    {
        $this->organization = $organization;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        if ($this->user->getOrganization() !== $this->organization->getId()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
