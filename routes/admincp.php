<?php

use App\Http\Middleware;
use Contracts\Routing\{Router, RouteMiddleware};

/**
 *------------------------------------------------------------------------------
 *
 *  Admin Control Panel
 *
 */

$r->group(
    $r->name('admincp.')
      ->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Auth\Admincp\IsAdmin::class);
          $m->push(Middleware\Auth\Admincp\ManagesOrganization::class);
      })
      ->pattern('/admincp'),
    function (Router $r) {
        foreach ([
            'admin-position', 'admin', 'bank', 'game',
            'ladder-gametype', 'ladder', 'organization', 'platform'
        ] as $file) {
            require realpath(__DIR__) . "/admincp/{$file}.php";
        }
    }
);
