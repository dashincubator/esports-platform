<?php

namespace App\Bootstrap\Providers\Contracts;

use Contracts\Database\{Connection, Driver, Server};
use App\Bootstrap\Providers\AbstractProvider;

class DatabaseProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerConnectionBinding();
    }


    private function registerConnectionBinding() : void
    {
        $concrete = $this->container->getAlias(Connection::class);

        $this->container->singleton(Connection::class, function() use ($concrete) {
            $driver = $this->container->resolve(Driver::class);
            $server = $this->container->resolve(Server::class, [
                $this->config->get('contracts.database.charset'),
                $this->config->get('contracts.database.host'),
                $this->config->get('contracts.database.name'),
                $this->config->get('contracts.database.password'),
                (int) $this->config->get('contracts.database.port'),
                $this->config->get('contracts.database.socket'),
                $this->config->get('contracts.database.user')
            ]);

            return $this->container->resolve($concrete, [$server, $driver->buildDsn($server), $this->config->get('contracts.database.options', [])]);
        });
    }
}
