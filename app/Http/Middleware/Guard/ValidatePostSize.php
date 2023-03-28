<?php

namespace App\Http\Middleware\Guard;

use App\Flash;
use App\Http\Responders\Redirect as Responder;
use Closure;
use Contracts\Http\{Middleware as Contract, Request, Response};
use Exception;

class ValidatePostSize implements Contract
{

    private $flash;

    private $responder;


    public function __construct(Flash $flash, Responder $responder)
    {
        $this->flash = $flash;
        $this->responder = $responder;
    }


    private function getMaxPostSize()
    {
        $size = ini_get('post_max_size');

        if (is_numeric($size)) {
            return (int) $size;
        }

        $metric = strtoupper(mb_substr($size, -1));
        $size = (int) $size;

        switch ($metric) {
            case 'K':
                return $size * 1024;
            case 'M':
                return $size * 1048576;
            case 'G':
                return $size * 1073741824;
            default:
                return $size;
        }
    }


    public function handle(Request $request, Closure $next) : Response
    {
        $size = $this->getMaxPostSize();

        if ($size > 0 && $request->getServer()->get('CONTENT_LENGTH', 0) > $size) {
            $this->flash->error('File upload exceeds maximum size, please resize and try again');

            return $this->responder->render($request->getPreviousUrl());
        }

        return $next($request);
    }
}
