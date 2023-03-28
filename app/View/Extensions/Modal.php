<?php

namespace App\View\Extensions;

use Contracts\Collections\Associative as Collection;

class Modal
{

    private $collection;


    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }


    public function add(string $key, array $data = []) : void
    {
        $this->collection->set($key, $data);
    }


    public function toArray() : array
    {
        return $this->collection->toArray();
    }
}
