<?php

namespace App\Bootstrap\Providers\View\Extensions;

use App\Bootstrap\Providers\AbstractProvider;
use App\View\Extensions\Time;

class TimeProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->bind(Time::class, null, [$this->config->get('timezones.list')]);
    }
}
