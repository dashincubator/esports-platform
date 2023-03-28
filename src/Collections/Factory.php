<?php declare(strict_types=1);

namespace System\Collections;

use Contracts\Collections\{Associative, Factory as Contract, Queue, Sequential, Stack};
use Contracts\Container\Container;

class Factory implements Contract
{

    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function createAssociative(array $values = []) : Associative
    {
        return $this->container->resolve(Associative::class, [$values]);
    }


    public function createQueue() : Queue
    {
        return $this->container->resolve(Queue::class);
    }


    public function createSequential(array $values = []) : Sequential
    {
        return $this->container->resolve(Sequential::class, [$values]);
    }


    public function createStack() : Stack
    {
        return $this->container->resolve(Stack::class);
    }
}
