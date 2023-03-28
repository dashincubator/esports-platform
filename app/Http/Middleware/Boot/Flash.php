<?php

namespace App\Http\Middleware\Boot;

use App\Flash as Manager;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};
use Contracts\Session\Session;

class Flash implements Contract
{

    private const SESSION_KEY = 'flash';


    private $flash;

    private $session;


    public function __construct(Manager $flash, Session $session)
    {
        $this->flash = $flash;
        $this->session = $session;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $this->retrieveFromSession();

        $response = $next($request);

        $this->storeInSession();

        return $response;
    }


    private function retrieveFromSession() : void
    {
        $flash = $this->session->get(self::SESSION_KEY, []);

        foreach ($flash as $method => $data) {
            if (!method_exists($this->flash, $method)) {
                continue;
            }

            $this->flash->{$method}($data);
        }
    }


    private function storeInSession()
    {
        $this->session->flash(self::SESSION_KEY, $this->flash->toArray());
    }
}
