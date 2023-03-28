<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Auth\Admincp\ManagesLadderGametypes::class);
      })
      ->name('ladder.gametype.')
      ->pattern('/ladder-gametype'),
    function (Router $r) {
        $r->get('manage', '/manage', Web\Admincp\LadderGametypes\Action::class);
        $r->get('create', '/create', Web\Admincp\LadderGametype\Action::class, 'create');

        $r->post('create.command', '/create', Commands\Ladder\Gametype\Create\Action::class)->middleware(function(RouteMiddleware $m) {
            $m->push(Middleware\Auth\Admincp\ManagesSubmittedGame::class);
        });

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                  $m->push(Middleware\FindById\LadderGametype::class);
                  $m->push(Middleware\Auth\Admincp\ManagesGametypeGame::class);
              })
              ->pattern('/{id:int}'),
            function (Router $r) {
                $r->get('edit', '/edit', Web\Admincp\LadderGametype\Action::class, 'edit');

                $r->post('delete.command', '/delete', Commands\Ladder\Gametype\Delete\Action::class);
                $r->post('update.command', '/update', Commands\Ladder\Gametype\Update\Action::class)->middleware(function(RouteMiddleware $m) {
                    $m->push(Middleware\Auth\Admincp\ManagesSubmittedGame::class);
                });
            }
        );
    }
);
