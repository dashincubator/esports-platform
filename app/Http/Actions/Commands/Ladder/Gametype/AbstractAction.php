<?php

namespace App\Http\Actions\Commands\Ladder\Gametype;

use App\Http\Actions\AbstractAction as AbstractParent;
use Contracts\Collections\Associative as Collection;

abstract class AbstractAction extends AbstractParent
{

    public function input(Collection $collection) : array
    {
        $input = $collection->intersect(['game', 'name']);

        foreach ([
            'bestOf', 'mapsets', 'modifiers', 'playersPerTeam', 'teamsPerMatch'
        ] as $key) {
            $input[$key] = $collection->get($key, []);
        }

        return $input;
    }
}
