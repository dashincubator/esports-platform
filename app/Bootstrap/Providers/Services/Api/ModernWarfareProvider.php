<?php

namespace App\Bootstrap\Providers\Services\Api;

use App\Bootstrap\Providers\AbstractProvider;
use App\Services\Api\ModernWarfare\Auth;

class ModernWarfareProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerAuthProvider();
    }


    private function registerAuthProvider() : void
    {
        $concrete = $this->container->getAlias(Auth::class);

        $this->container->bind(Auth::class, function() use ($concrete) {
            $accounts = $this->config->get('api.modernwarfare.accounts.production', []);

            if ($this->config->get('app.debug')) {
                $accounts = $this->config->get('api.modernwarfare.accounts.debug', []);
            }

            return $this->container->resolve($concrete, [$accounts]);
        });
    }
}
