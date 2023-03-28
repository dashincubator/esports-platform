<?php

namespace App\Http\Middleware\Guard;

use App\Flash;
use App\Http\Responders\Redirect as Responder;
use Closure;
use Contracts\Http\{Factory, Middleware as Contract, Request, Response};
use Contracts\Session\Session;
use Exception;

class CsrfTokens implements Contract
{

    private const TOKEN_NAME = 'CSRF';


    private $factory;

    private $flash;

    private $session;

    private $domain;

    private $isHttpOnly;

    private $isSecure;

    private $lifetime;

    private $path;

    private $responder;

    private $skip;

    private $strength;


    public function __construct(
        Factory $factory,
        Flash $flash,
        Responder $responder,
        Session $session,
        string $domain,
        bool $isHttpOnly,
        bool $isSecure,
        int $lifetime,
        string $path,
        array $skip = [],
        int $strength = 16
    ) {
        $this->factory = $factory;
        $this->flash = $flash;
        $this->responder = $responder;
        $this->session = $session;

        $this->domain = $domain;
        $this->isHttpOnly = $isHttpOnly;
        $this->isSecure = $isSecure;
        $this->lifetime = $lifetime;
        $this->path = $path;
        $this->skip = $skip;
        $this->strength = $strength;
    }


    private function getTokenValue() : string
    {
        if (!$this->session->has(self::TOKEN_NAME)) {
            $this->regenerateToken();
        }

        return $this->session->get(self::TOKEN_NAME);
    }


    public function handle(Request $request, Closure $next) : Response
    {
        if (!$this->isValidToken($request)) {
            $this->flash->error('Invalid form token, please try again');
            $this->flash->input($request->getInput()->toArray());

            $response = $this->responder->render($request->getPreviousUrl());
        }
        else {
            $response = $next($request);
        }

        return $this->writeToResponse($response);
    }


    private function isReading(Request $request) : bool
    {
        return in_array($request->getMethod(), ['HEAD', 'GET', 'OPTIONS']);
    }


    private function isValidToken(Request $request) : bool
    {
        if (
            $this->isReading($request)
            || in_array($request->getAttributes()->get('route.name'), $this->skip)
        ) {
            return true;
        }

        return hash_equals($this->getTokenValue(), $request->getCookies()->get(self::TOKEN_NAME, ''));
    }


    private function regenerateToken() : void
    {
        $this->session->set(self::TOKEN_NAME, bin2hex(random_bytes($this->strength)));
    }


    private function writeToResponse(Response $response) : Response
    {
        $response->getHeaders()->setCookie(
            $this->factory->createResponseCookie(
                self::TOKEN_NAME,
                $this->getTokenValue(),
                time() + $this->lifetime,
                $this->path,
                $this->domain,
                $this->isSecure,
                $this->isHttpOnly,
                'Strict'
            )
        );

        return $response;
    }
}
