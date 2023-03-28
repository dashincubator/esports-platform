<?php

use App\Http\Actions\{Commands, Web};
use App\Http\Middleware;
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->middleware(function(RouteMiddleware $m) {
          $m->push(Middleware\Auth\Admincp\ManagesAdminPositions::class);
      })
      ->name('admin.position.')
      ->pattern('/admin-position'),
    function (Router $r) {
        $r->get('create', '/create', Web\Admincp\AdminPosition\Action::class, 'create');

        $r->post('create.command', '/create', Commands\User\AdminPosition\Create\Action::class);

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                  $m->push(Middleware\FindById\AdminPosition::class);
                  $m->push(Middleware\Auth\Admincp\ManagesAdminPositionOrganization::class);
              })
              ->pattern('/{id:int}'),
            function (Router $r) {
                $r->get('edit', '/edit', Web\Admincp\AdminPosition\Action::class, 'edit');

                $r->post('delete.command', '/delete', Commands\User\AdminPosition\Delete\Action::class);
                $r->post('update.command', '/update', Commands\User\AdminPosition\Update\Action::class);
            }
        );
    }
);
