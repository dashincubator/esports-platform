<?php

namespace App\Bootstrap\Providers\Http\Middleware;

use Contracts\Encryption\Encrypter;
use App\Http\Middleware\Boot\CookieEncryption;
use App\Bootstrap\Providers\AbstractProvider;

class CookieEncryptionProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->bind(CookieEncryption::class, null, [
            $this->config->get('contracts.session.encryption.skip', []),
            $this->config->get('contracts.session.encryption.use', false)
        ]);

        $this->container->when(CookieEncryption::class)
            ->needs(Encrypter::class)
            ->give(function() {
                return $this->container->resolve(Encrypter::class, [$this->config->get('contracts.cookies.encryption.key')]);
            });
    }
}
