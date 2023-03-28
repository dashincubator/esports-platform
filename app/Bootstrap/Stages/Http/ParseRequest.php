<?php

namespace App\Bootstrap\Stages\Http;

use Contracts\Bootstrap\Application;
use Contracts\Http\{Factory, Response};
use Closure;

class ParseRequest
{

    private $factory;


    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }


    public function handle(Application $app, Closure $next) : Response
    {
        return $next($this->factory->createRequestFromGlobals());
    }
}
