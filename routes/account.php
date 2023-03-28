<?php

use App\Http\Middleware;
use Contracts\Routing\{Router, RouteMiddleware};

/**
 *------------------------------------------------------------------------------
 *
 *  Account Management
 *
 */

$r->group(
    $r->name('account.')
      ->pattern('/account'),
    function (Router $r) {

        require realpath(__DIR__) . "/account/auth.php";

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                  $m->push(Middleware\Redirect\IfGuest::class);
              }),
            function (Router $r) {
                foreach ([
                    'bank', 'edit', 'membership', 'update'
                ] as $file) {
                    require realpath(__DIR__) . "/account/{$file}.php";
                }
            }
        );
    }
);
