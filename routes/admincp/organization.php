<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Auth\Admincp\ManagesOrganizations::class);
      })
      ->name('organization.')
      ->pattern('/organization'),
    function (Router $r) {
        $r->get('manage', '/manage', Web\Admincp\Organizations\Action::class);
        $r->get('create', '/create', Web\Admincp\Organization\Action::class, 'create');

        $r->post('create.command', '/create', Commands\Organization\Create\Action::class);

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                  $m->push(Middleware\FindById\Organization::class);
              })
              ->pattern('/{id:int}'),
            function (Router $r) {
                $r->get('edit', '/edit', Web\Admincp\Organization\Action::class, 'edit');

                $r->post('delete.command', '/delete', Commands\Organization\Delete\Action::class);
                $r->post('update.command', '/update', Commands\Organization\Update\Action::class);
            }
        );
    }
);
