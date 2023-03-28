<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Auth\Admincp\ManagesGames::class);
      })
      ->name('game.')
      ->pattern('/game'),
    function (Router $r) {
        $r->get('create', '/create', Web\Admincp\Game\Action::class, 'create');

        $r->post('create.command', '/create', Commands\Game\Create\Action::class);

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                  $m->push(Middleware\FindById\Game::class);
              })
              ->pattern('/{id:int}'),
            function (Router $r) {
                $r->get('edit', '/edit', Web\Admincp\Game\Action::class, 'edit');

                $r->post('delete.command', '/delete', Commands\Game\Delete\Action::class);
                $r->post('update.command', '/update', Commands\Game\Update\Action::class);
            }
        );
    }
);
