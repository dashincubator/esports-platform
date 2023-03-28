<?php

namespace App\View\Extensions;

use Contracts\Collections\Stack as Collection;

class Anchor
{

    private $collection;


    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }


    public function add(string $anchors) : void
    {
        $this->collection->add($anchors);
    }


    public function toArray() : array
    {
        return $this->collection->toArray();
    }
}
