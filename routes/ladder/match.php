<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Redirect\IfGuest::class);
      })
      ->pattern('/match'),
    function (Router $r) {
        $r->group(
            $r->name('match.'),
            function (Router $r) {
                $r->post('accept.command', '/accept', Commands\Ladder\Match\Accept\Action::class);
                $r->post('cancel.command', '/cancel', Commands\Ladder\Match\Cancel\Action::class);
                $r->post('create.command', '/create', Commands\Ladder\Match\Create\Action::class);
            }
        );

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                  $m->push(Middleware\FindBySlugs\Ladder\Match::class);
              })
              ->pattern('/{match:int}'),
            function (Router $r) {
                $r->get('match', '', Web\Ladder\Match\Action::class);

                $r->group(
                    $r->name('match.'),
                    function (Router $r) {
                        $r->post('report.command', '/report/{report:int}', Commands\Ladder\Match\Report\Action::class)->middleware(function(RouteMiddleware $m) {
                            $m->push(Middleware\Auth\Ladder\Match\Report::class);
                        });
                    }
                );
            }
        );
    }
);
