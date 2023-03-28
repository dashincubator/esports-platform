<?php

namespace App\Http\Actions\Commands\Game;

use App\Http\Actions\AbstractAction as AbstractParent;
use Contracts\Collections\Associative as Collection;

abstract class AbstractAction extends AbstractParent
{

    public function input(Collection $collection) : array
    {
        return $collection->intersect(['account', 'name', 'platform', 'slug', 'view']);
    }
}
