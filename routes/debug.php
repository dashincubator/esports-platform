<?php

use App\Http\Actions\Web;
use App\Http\Middleware;
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->name('debug.')
      ->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Guard\DebugRequired::class);
      })
      ->pattern('/debug'),
    function (Router $r) {
        $r->get('errors', '/errors[/{limit:int}]', Web\Error\Log\Action::class, 'view');
        $r->get('errors.delete', '/errors/delete', Web\Error\Log\Action::class, 'delete');
    }
);
