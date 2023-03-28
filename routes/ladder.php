<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\FindBySlugs\Game::class);
          $m->push(Middleware\FindBySlugs\Ladder::class);
      })
      ->pattern('/{platform:slug}/{game:slug}/ladder/{ladder:slug}'),
    function (Router $r) {
        $r->get('ladder', '', Web\Ladder\Leaderboard\Action::class);

        $r->group(
            $r->name('ladder.'),
            function (Router $r) {
                $r->get('faq', '/faq', Web\Ladder\Leaderboard\Action::class);
                $r->get('leaderboard', '/leaderboard[/{page:int}]', Web\Ladder\Leaderboard\Action::class);
                $r->get('prizes', '/prizes', Web\Ladder\Leaderboard\Action::class);
                $r->get('rules', '/rules', Web\Ladder\Leaderboard\Action::class);
 
                $r->post('join.command', '/join', Commands\Ladder\Team\Create\Action::class)->middleware(function(RouteMiddleware $m) {
                    $m->push(Middleware\Redirect\IfGuest::class);
                });
                $r->get('matchfinder', '/matchfinder', Web\Ladder\MatchFinder\Action::class)->middleware(function(RouteMiddleware $m) {
                    $m->push(Middleware\Ladder\MatchFinder::class);
                });

                foreach (['match', 'team'] as $file) {
                    require realpath(__DIR__) . "/ladder/{$file}.php";
                }
            }
        );
    }
);
