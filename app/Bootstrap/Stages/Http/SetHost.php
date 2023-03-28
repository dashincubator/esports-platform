<?php

namespace App\Bootstrap\Stages\Http;

use Closure;
use Contracts\Configuration\Configuration;
use Contracts\Http\{Request, Response};

class SetHost
{

    private $config;


    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $host = array_reverse(explode('://', $request->getHost()))[0];
        $host = array_reverse(explode('www.', $host))[0];

        if ($host && $host !== 'localhost') {
            $this->config->set('app.host', $host);
        }

        return $next($request);
    }
}
