<?php

namespace App\Bootstrap\Providers\Contracts;

use App\Bootstrap\Providers\AbstractProvider;
use Contracts\Session\Session;

class SessionProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->singleton(Session::class);
    }
}
