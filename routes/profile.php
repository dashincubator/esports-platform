<?php

use App\Http\Middleware;
use App\Http\Actions\Web;
use Contracts\Routing\RouteMiddleware;

$r->get('profile', '/{slug:slug}', Web\Profile\Action::class)->middleware(function(RouteMiddleware $m) {
    $m->push(Middleware\FindBySlugs\Profile::class);
});
