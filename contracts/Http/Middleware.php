<?php declare(strict_types=1);

namespace Contracts\Http;

use Closure;

interface Middleware 
{

    /**
     *  Do Something...
     */
    public function handle(Request $request, Closure $next) : Response;
}
