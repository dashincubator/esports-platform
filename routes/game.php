<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->name('game.')
      ->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\FindBySlugs\Game::class);
      })
      ->pattern('/{platform:slug}/{game:slug}'),
    function (Router $r) {
        $r->get('leaderboard', '/leaderboard[/{page:int}]', Web\Game\Leaderboard\Action::class);
    }
);
