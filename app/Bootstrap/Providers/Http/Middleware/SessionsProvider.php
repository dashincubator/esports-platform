<?php declare(strict_types=1);

namespace App\Bootstrap\Providers\Http\Middleware;

use App\Bootstrap\Providers\AbstractProvider;
use App\Http\Middleware\Boot\Sessions;
use Contracts\Cache\{Cache, Redis};
use Contracts\Encryption\Encrypter;

class SessionsProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->bind(Sessions::class, null, [
            $this->config->get('contracts.session.cookie.domain'),
            $this->config->get('contracts.session.cookie.isHttpOnly'),
            $this->config->get('contracts.session.cookie.isSecure'),
            $this->config->get('contracts.session.lifetime'),
            $this->config->get('contracts.session.name'),
            $this->config->get('contracts.session.cookie.path')
        ]);

        $this->container->when(Sessions::class)
            ->needs(Cache::class)
            ->give(function() {
                $store = $this->container->resolve(Redis::class, [$this->config->get('contracts.session.cache.client'), $this->config->get('contracts.session.cache.prefix')]);

                if ($this->config->get('contracts.session.encryption.use', false)) {
                    $store->setEncrypter($this->container->resolve(Encrypter::class, [$this->config->get('contracts.session.encryption.key')]));
                    $store->useEncryption(true);
                }

                return $store;
            });
    }
}
