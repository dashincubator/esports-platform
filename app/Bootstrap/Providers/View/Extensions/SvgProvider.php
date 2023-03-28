<?php

namespace App\Bootstrap\Providers\View\Extensions;

use App\Bootstrap\Providers\AbstractProvider;
use App\View\Extensions\Svg;

class SvgProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->bind(Svg::class, null, [$this->config->get('paths.public.svg')]);
    }
}
