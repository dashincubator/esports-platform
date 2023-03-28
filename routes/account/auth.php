<?php

use App\Http\Middleware;
use App\Http\Actions\{Commands, Web};
use Contracts\Routing\{Router, RouteMiddleware};

$r->group(
    $r->name('auth.'),
    function (Router $r) {

        $r->get('sign-out.command', '/sign-out', Commands\User\SignOut\Action::class);

        $r->group(
            $r->middleware(function(RouteMiddleware $m) {
                $m->push(Middleware\Redirect\IfUser::class);
            }),
            function (Router $r) {
                $r->get('forgot-password', '/forgot-password', Web\Index\Action::class);
                $r->post('forgot-password.command', '/forgot-password', Commands\User\ForgotPassword\Create\Action::class);

                $r->get('reset-password', '/reset-password/{id:int}/{code}', Web\Index\Action::class);
                $r->post('reset-password.command', '/reset-password', Commands\User\ForgotPassword\ResetPassword\Action::class);

                $r->get('sign-in', '/sign-in', Web\Index\Action::class);
                $r->post('sign-in.command', '/sign-in', Commands\User\SignIn\Action::class);

                $r->get('sign-up', '/new', Web\Index\Action::class);
                $r->post('sign-up.command', '/new', Commands\User\SignUp\Action::class);
            }
        );
    }
);
