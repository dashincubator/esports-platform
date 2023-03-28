<?php

namespace App\Http\Actions\Commands\Organization;

use App\Http\Actions\AbstractAction as AbstractParent;
use Contracts\Collections\Associative as Collection;

abstract class AbstractAction extends AbstractParent
{

    public function input(Collection $collection) : array
    {
        $input = $collection->intersect(['domain', 'name', 'paypal', 'user']);

        if (array_key_exists('user', $input) && !$input['user']) {
            unset($input['user']);
        }

        return $input;
    }
}
