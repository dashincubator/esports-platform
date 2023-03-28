<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Auth\Admincp\ManagesAdmin::class);
      })
      ->name('admin.')
      ->pattern('/admin'),
    function (Router $r) {
        $r->get('manage', '/manage', Web\Admincp\Admin\Action::class);

        $r->post('update.command', '/update', Commands\User\Update\Admin\Action::class);
    }
);
