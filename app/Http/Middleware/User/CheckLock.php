<?php

namespace App\Http\Middleware\User;

use App\{Flash, User};
use App\DataSource\User\Lock\Message\Mapper;
use App\Http\Responders\Redirect as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};

class CheckLock implements Contract
{

    private $flash;

    private $mapper;

    private $responder;

    private $user;


    public function __construct(Flash $flash, Mapper $mapper, Responder $responder, User $user)
    {
        $this->flash = $flash;
        $this->mapper = $mapper;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request, Closure $next) : Response
    {
        if ($this->user->isLocked()) {
            $message = $this->mapper->findLatestByUser($this->user->getId());

            if (!$message->isEmpty()) {
                $this->flash->error($message->getContent());

                return $this->responder->render('index');
            }
        }

        return $next($request);
    }
}
