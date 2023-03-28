<?php

namespace App\Http\Actions\Commands\Ladder;

use App\Http\Actions\AbstractAction as AbstractParent;
use Contracts\Http\Request;

abstract class AbstractAction extends AbstractParent
{

    public function input(Request $request) : array
    {
        $input = $request->getInput()->intersect([
            'endsAt', 'entryFee', 'entryFeePrizes',
            'firstToScore', 'format',
            'game', 'gametypes',
            'maxPlayersPerTeam', 'membershipRequired', 'minPlayersPerTeam',
            'name',
            'payout', 'prizePool', 'prizes', 'prizesAdjusted',
            'rules',
            'slug', 'startsAt', 'stopLoss'
        ]);
        $input['gametypes'] = $input['gametypes'][$input['game']] ?? [];

        foreach (['membershipRequired', 'prizesAdjusted'] as $key) {
            $input[$key] = (array_key_exists($key, $input) ? (bool) $input[$key] : null);
        }

        foreach (['endsAt', 'startsAt'] as $key) {
            $input[$key] = (array_key_exists($key, $input) ? strtotime($input[$key]) : null);

            if (!$input[$key]) {
                $input[$key] = null;
            }
        }

        foreach (['entryFeePrizes', 'prizes', 'rules'] as $key) {
            if (!array_key_exists($key, $input)) {
                continue;
            }

            // First Layer Keys Are Throwaway
            // - They Are Unique Keys For UI Templates
            $input[$key] = array_values($input[$key]);
        }

        return array_merge($input, $request->getFiles()->intersect(['banner', 'card']));
    }
}
