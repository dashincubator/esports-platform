<?php

namespace App\Http\Middleware\Ladder;

use App\Http\Responders\Html as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class MatchFinder implements Contract
{

    private $responder;


    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $ladder = $request->getAttributes()->get('ladder');

        if (!$ladder->isMatchFinderRequired()) {
            return $this->responder->render404();
        }

        return $next($request);
    }
}
