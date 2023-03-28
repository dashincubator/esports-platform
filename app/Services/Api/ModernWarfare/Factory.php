<?php

namespace App\Services\Api\ModernWarfare;

use Contracts\Container\Container;

class Factory
{

    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function createAuthTokens() : AuthTokens
    {
        return $this->container->resolve(AuthTokens::class);
    }


    public function createAuthToken(string $email, array $headers) : AuthToken
    {
        return $this->container->resolve(AuthToken::class, [$email, $headers]);
    }
}
