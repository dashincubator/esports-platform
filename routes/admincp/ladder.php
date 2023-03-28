<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Auth\Admincp\ManagesLadders::class);
      })
      ->name('ladder.')
      ->pattern('/ladder'),
    function (Router $r) {
        $r->get('create', '/create', Web\Admincp\Ladder\Action::class, 'create');

        $r->post('create.command', '/create', Commands\Ladder\Create\Action::class)->middleware(function(RouteMiddleware $m) {
            $m->push(Middleware\Auth\Admincp\ManagesSubmittedGame::class);
        });

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                  $m->push(Middleware\FindById\Ladder::class);
                  $m->push(Middleware\Auth\Admincp\ManagesLadderOrganization::class);
                  $m->push(Middleware\Auth\Admincp\ManagesLadderGame::class);
              })
              ->pattern('/{id:int}'),
            function (Router $r) {
                $r->get('edit', '/edit', Web\Admincp\Ladder\Action::class, 'edit');

                $r->post('delete.command', '/delete', Commands\Ladder\Delete\Action::class);
                $r->post('payout.command', '/payout', Commands\Ladder\Payout\Action::class);
                $r->post('update.command', '/update', Commands\Ladder\Update\Action::class)->middleware(function(RouteMiddleware $m) {
                    $m->push(Middleware\Auth\Admincp\ManagesSubmittedGame::class);
                });
            }
        );
    }
);
