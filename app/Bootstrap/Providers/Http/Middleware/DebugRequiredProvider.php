<?php

namespace App\Bootstrap\Providers\Http\Middleware;

use App\Http\Middleware\Guard\DebugRequired;
use App\Bootstrap\Providers\AbstractProvider;

class DebugRequiredProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->bind(DebugRequired::class, null, [$this->config->get('app.debug')]);
    }
}
