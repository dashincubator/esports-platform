<?php

namespace App\Bootstrap\Providers;

use App\Flash;
use App\Bootstrap\Providers\AbstractProvider;

class FlashProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->singleton(Flash::class);
    }
}
