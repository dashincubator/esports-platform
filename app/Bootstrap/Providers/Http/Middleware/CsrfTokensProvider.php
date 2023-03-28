<?php

namespace App\Bootstrap\Providers\Http\Middleware;

use App\Http\Middleware\Guard\CsrfTokens;
use App\Bootstrap\Providers\AbstractProvider;

class CsrfTokensProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->bind(CsrfTokens::class, null, [
            $this->config->get('contracts.session.cookie.domain'),
            $this->config->get('contracts.session.cookie.isHttpOnly'),
            $this->config->get('contracts.session.cookie.isSecure'),
            $this->config->get('contracts.session.csrf.lifetime'),
            $this->config->get('contracts.session.cookie.path'),
            $this->config->get('contracts.csrf.routes.skip', [])
        ]);
    }
}
