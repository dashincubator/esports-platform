<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Auth\Admincp\ManagesGames::class);
      })
      ->name('platform.')
      ->pattern('/platform'),
    function (Router $r) {
        $r->get('create', '/create', Web\Admincp\Platform\Action::class, 'create');

        $r->post('create.command', '/create', Commands\Game\Platform\Create\Action::class);

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                  $m->push(Middleware\FindById\Platform::class);
              })
              ->pattern('/{id:int}'),
            function (Router $r) {
                $r->get('edit', '/edit', Web\Admincp\Platform\Action::class, 'edit');

                $r->post('delete.command', '/delete', Commands\Game\Platform\Delete\Action::class);
                $r->post('update.command', '/update', Commands\Game\Platform\Update\Action::class);
            }
        );
    }
);
