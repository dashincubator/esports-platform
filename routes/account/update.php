<?php

use App\Http\Actions\Commands;
use Contracts\Routing\Router;

$r->group(
    $r->name('update.')
      ->pattern('/update'),
    function(Router $r) {
        $r->post('password.command', '/password', Commands\User\Update\Password\Action::class);
        $r->post('profile.command', '/profile', Commands\User\Update\Profile\Action::class);
    }
);
