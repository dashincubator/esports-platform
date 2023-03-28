<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\FindBySlugs\Ladder\Team::class);
      })
      ->pattern('/team/{team:slug}'),
    function (Router $r) {
        $r->get('team', '', Web\Ladder\Team\Profile\Action::class);

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                  $m->push(Middleware\Redirect\IfGuest::class);
              })
              ->name('team.'),
            function (Router $r) {
                $r->post('delete.command', '/delete', Commands\Ladder\Team\Delete\Action::class)->middleware(function(RouteMiddleware $m) {
                    $m->push(Middleware\Auth\Ladder\Team\Edit::class);
                });

                $r->group(
                  $r->name('invite.')
                    ->pattern('/invite'),
                  function (Router $r) {
                      $r->post('create.command', '/create', Commands\Ladder\Team\Invite\Create\Action::class)->middleware(function(RouteMiddleware $m) {
                          $m->push(Middleware\Auth\Ladder\Team\Edit::class);
                      });
                      $r->post('respond.command', '/respond', Commands\Ladder\Team\Invite\Respond\Action::class);
                  }
                );

                $r->group(
                    $r->middleware(function(RouteMiddleware $m) {
                          $m->push(Middleware\Auth\Ladder\Team\Edit::class);
                      })
                      ->name('update.')
                      ->pattern('/update'),
                    function (Router $r) {
                        $r->post('profile.command', '/profile', Commands\Ladder\Team\Update\Profile\Action::class);
                        $r->post('roster.command', '/roster', Commands\Ladder\Team\Update\Roster\Action::class);
                    }
                );

                $r->post('leave.command', '/leave', Commands\Ladder\Team\Member\Leave\Action::class);
                $r->post('lock.command', '/lock', Commands\Ladder\Team\Lock\Action::class)->middleware(function(RouteMiddleware $m) {
                    $m->push(Middleware\Auth\Ladder\Team\Edit::class);
                });
            }
        );
    }
);
