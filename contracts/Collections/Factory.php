<?php declare(strict_types=1);

namespace Contracts\Collections;

interface Factory
{

    public function createAssociative(array $values = []) : Associative;
    public function createQueue() : Queue;
    public function createSequential(array $values = []) : Sequential;
    public function createStack() : Stack;
}
