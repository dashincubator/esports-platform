<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->name('bank.')
      ->pattern('/bank'),
    function (Router $r) {
        $r->get('deposits', '/deposits[/{page:int}]', Web\Account\Bank\Action::class, 'deposits');
        $r->get('transactions', '/transactions[/{page:int}]', Web\Account\Bank\Action::class, 'transactions');
        $r->get('withdraws', '/withdraws[/{page:int}]', Web\Account\Bank\Action::class, 'withdraws');
        
        $r->post('withdraw.command', '/withdraw', Commands\User\Bank\Withdraw\Create\Action::class);

        $r->post('deposit.command', '/deposit', Commands\User\Bank\Deposit\Action::class)
          ->middleware(function(RouteMiddleware $m) {
              $m->push(Middleware\Redirect\IfGuest::class);
          });

        $r->group(
            $r->name('square.')
              ->pattern('/square'),
            function (Router $r) {
                $r->get('webhook.command', '/webhook', Commands\User\Bank\Deposit\Square\Confirm\Action::class);
            }
        );

        $r->group(
            $r->name('paypal.')
              ->pattern('/paypal'),
            function (Router $r) {
                $r->post('webhook.command', '/webhook', Commands\User\Bank\Deposit\Paypal\IPN\Action::class)
                  ->middleware(function(RouteMiddleware $m) {
                      $m->replace([Middleware\Guard\ForceHttps::class]);
                  });
            }
        );
    }
);
