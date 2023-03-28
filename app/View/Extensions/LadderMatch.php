<?php

namespace App\View\Extensions;

use Contracts\View\Extensions\Data;

class LadderMatch
{

    private $time;


    public function __construct(Time $time)
    {
        $this->time = $time;
    }


    public function toTextListArray(Data $match) : array
    {
        $status = 'In Matchfinder';

        foreach (['isActive', 'isComplete', 'isDisputed', 'isUpcoming'] as $key) {
            if (!$match[$key]) {
                continue;
            }

            $status = ucfirst(ltrim($key, 'is'));
        }

        return array_filter([
            [
                'svg' => 'match',
                'text' => $match['bestOf'],
                'title' => 'Best Of'
            ],
            [
                'svg' => 'id',
                'text' => $match['id'],
                'title' => 'Match ID'
            ],
            [
                'svg' => 'calendar',
                'text' => ($match['startedAt'] > 0)
                    ? $this->time->toMatchFormat($match['startedAt'])
                    : '-',
                'title' => 'Match Starts'
            ],
            [
                'svg' => 'settings',
                'text' => (count($match['modifiers']) > 0 )
                    ? $match['modifiers']
                    : 'None ( Default )',
                'title' => 'Modifiers'
            ],
            [
                'svg' => 'user',
                'text' => "{$match['playersPerTeam']} Player" . ($match['playersPerTeam'] > 1 ? 's' : ''),
                'title' => 'Players Per Team'
            ],
            [
                'svg' => 'status-circle',
                'text' => $status,
                'title' => 'Status'
            ],
            [
                'svg' => 'team',
                'text' => $match['teamsPerMatch'],
                'title' => 'Teams Per Match'
            ],
            (($match['wager'] > 0) ? [
                'svg' => 'trophy',
                'text' => '$' . (number_format(($match['wager'] * $match['playersPerTeam'] * $match['teamsPerMatch']), 2) + 0),
                'title' => 'Total Pot'
            ] : []),
            (($match['wager'] > 0) ? [
                'svg' => 'dollar',
                'text' => '$' . (number_format($match['wager'], 2) + 0),
                'title' => 'Wager Per Player'
            ] : [])
        ]);
    }


    public function toTextListArrayShort(Data $match) : array
    {
        $items = $this->toTextListArray($match);

        foreach ($items as $index => $item) {
            if (in_array($item['title'], ['Match ID', 'Match Starts', 'Match Support', 'Status'])) {
                unset($items[$index]);
            }
        }

        return $items;
    }
}
