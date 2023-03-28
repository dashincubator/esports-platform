<?php

namespace App\Bootstrap\Providers\Contracts;

use App\Bootstrap\Providers\AbstractProvider;
use Contracts\Redis\Manager;
use Exception;
use Redis as Client;

class RedisProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerManagerBinding();
    }


    private function registerManagerBinding() : void
    {
        $concrete = $this->container->getAlias(Manager::class);

        $this->container->singleton(Manager::class, function() use ($concrete) {
            $connections = [];

            foreach ($this->config->get('contracts.redis', []) as $key => $values) {
                $connection = $this->container->resolve(Client::class);
                $params = [];

                if ($this->config->has("contracts.redis.{$key}.socket")) {
                    $params[] = $this->config->get("contracts.redis.{$key}.socket");
                }
                else {
                    $params = [
                        $this->config->get("contracts.redis.{$key}.host"),
                        $this->config->get("contracts.redis.{$key}.port")
                    ];
                }

                $connection->pconnect(...$params);

                if ($this->config->has("contracts.redis.{$key}.password")) {
                    $authentication = $connection->auth($this->config->get("contracts.redis.{$key}.password"));

                    if (!$authentication) {
                        throw new Exception("Redis Client Failed To Authenticate '{$key}' Connection Using Password Provided");
                    }
                }

                $connections[$key] = $connection;
            }

            return $this->container->resolve($concrete, [$connections]);
        });
    }
}
