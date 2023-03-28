<?php

namespace App\Http\Middleware\Guard;

use App\Http\Responders\Redirect as Responder;
use Closure;
use Contracts\Configuration\Configuration;
use Contracts\Http\{Middleware as Contract, Request, Response};

class ForceHttps implements Contract
{

    private $config;

    private $responder;


    public function __construct(Configuration $config, Responder $responder)
    {
        $this->config = $config;
        $this->responder = $responder;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        if (!$request->isSecure() && !$this->config->get('app.debug')) {
            return $this->responder->render(str_replace('http:', 'https:', $request->getFullUrl()));
        }

        $response = $next($request);

        // 2 Years Was Recommended Time Used In Firefox Documentation https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security
        $response->getHeaders()->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');

        return $response;
    }
}
