<?php declare(strict_types=1);

namespace System\Mail;

use Contracts\Container\Container;
use Contracts\Mail\{Factory as Contract, Mail};

class Factory implements Contract
{

    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function createMail() : Mail
    {
        return $this->container->resolve(Mail::class);
    }
}
