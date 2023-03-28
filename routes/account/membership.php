<?php

use App\Http\Actions\Commands;
use App\Http\Middleware;
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->name('membership.')
      ->pattern('/membership'),
    function (Router $r) {
        $r->post('purchase.command', '/purchase', Commands\User\Membership\Purchase\Action::class);
    }
);
