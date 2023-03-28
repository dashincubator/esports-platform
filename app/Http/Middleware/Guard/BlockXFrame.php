<?php

namespace App\Http\Middleware\Guard;

use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class BlockXFrame implements Contract
{

    public function handle(Request $request, Closure $next) : Response
    {
        $response = $next($request);
        $response->getHeaders()->set('X-Frame-Options', 'SAMEORIGIN', false);

        return $response;
    }
}
