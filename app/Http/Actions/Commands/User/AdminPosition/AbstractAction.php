<?php

namespace App\Http\Actions\Commands\User\AdminPosition;

use App\Http\Actions\AbstractAction as AbstractParent;
use Contracts\Collections\Associative as Collection;

abstract class AbstractAction extends AbstractParent
{

    public function input(Collection $collection) : array
    {
        $input = $collection->intersect(['name', 'permissions']);

        foreach (['games'] as $key) {
            $input[$key] = $collection->get($key, []);
        }

        return $input;
    }
}
