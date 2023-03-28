<?php

namespace App\Http\Middleware\Boot;

use Closure;
use Contracts\Cache\Cache;
use Contracts\Http\{Factory, Middleware as Contract, Request, Response};
use Contracts\Session\Session;

class Sessions implements Contract
{

    private const PREVIOUS_URL_KEY = 'previous-url';


    private $cache;

    private $domain;

    private $factory;

    private $isHttpOnly;

    private $isSecure;

    private $lifetime;

    private $name;

    private $path;

    private $session;


    public function __construct(
        Cache $cache,
        Factory $factory,
        Session $session,
        string $domain,
        bool $isHttpOnly,
        bool $isSecure,
        int $lifetime,
        string $name,
        string $path
    ) {
        $this->cache = $cache;
        $this->factory = $factory;
        $this->session = $session;

        $this->domain = $domain;
        $this->isHttpOnly = $isHttpOnly;
        $this->isSecure = $isSecure;
        $this->lifetime = $lifetime;
        $this->name = $name;
        $this->path = $path;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $this->startSession($request);

        if ($this->session->has(self::PREVIOUS_URL_KEY)) {
            $request->setPreviousUrl($this->session->get(self::PREVIOUS_URL_KEY));
        }

        $response = $next($request);

        if (
            $request->getMethod() === 'GET'
            && !$request->getAttributes()->get('route.isFallback')
            && !$request->isAjax()
        ) {
            $this->session->set(self::PREVIOUS_URL_KEY, $request->getFullUrl());
        }

        $this->writeSession();

        return $this->writeToResponse($response);
    }


    private function startSession(Request $request) : void
    {
        if ($request->getCookies()->has($this->name)) {
            $id = $request->getCookies()->get($this->name);
        }

        $this->session->start($id ?? '');
        $this->session->replace($this->cache->get($this->session->getId(), function() {
            return [];
        }));
    }


    private function writeSession() : void
    {
        $this->session->ageFlashKeys();
        $this->cache->set($this->session->getId(), $this->session->toArray(), $this->lifetime);
    }


    private function writeToResponse(Response $response) : Response
    {
        $response->getHeaders()->setCookie(
            $this->factory->createResponseCookie(
                $this->name,
                $this->session->getId(),
                time() + $this->lifetime,
                $this->path,
                $this->domain,
                $this->isSecure,
                $this->isHttpOnly
            )
        );

        return $response;
    }
}
