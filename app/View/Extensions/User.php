<?php

namespace App\View\Extensions;

use Contracts\Configuration\Configuration;
use Contracts\View\Extensions\Data;

class User
{

    private $config;

    private $game;

    private $time;


    public function __construct(Configuration $config, Game $game, Time $time)
    {
        $this->config = $config;
        $this->game = $game;
        $this->time = $time;
    }


    public function getGameAccountOptions() : array
    {
        return $this->game->getAccountOptions();
    }


    public function getGameAccountTextList(Data $accounts) : array
    {
        $items = [];
        $options = $this->getGameAccountOptions();

        foreach ($accounts as $account) {
            if (!array_key_exists($account['name'], $options)) {
                continue;
            }

            $items[] = [
                'modifier' => $account['name'],
                'svg' => "platform/{$account['name']}",
                'text' => $account['value'],
                'title' => $options[$account['name']]
            ];
        }

        return $items;
    }


    public function getSocialAccountOptions() : array
    {
        return $this->config->get('social.accounts');
    }


    public function getSocialAccountTextList(Data $accounts) : array
    {
        $items = [];
        $options = $this->getSocialAccountOptions();

        foreach ($accounts as $account) {
            if (!array_key_exists($account['name'], $options)) {
                continue;
            }

            $items[] = [
                'modifier' => $account['name'],
                'svg' => "social/{$account['name']}",
                'text' => $account['value'],
                'title' => $options[$account['name']]
            ];
        }

        return $items;
    }


    public function toOrganizerStatTextListArray(Data $user) : array
    {
        return array_filter([
            [
                'svg' => 'id',
                'text' => $user['id']
            ]
        ]);
    }


    public function toStatTextListArray(Data $ranks, Data $user) : array
    {
        $losses = 0;
        $winnings = 0;
        $wins = 0;

        foreach ($ranks as $rank) {
            $losses += $rank['losses'];
            $winnings += $rank['earnings'];
            $wins += $rank['wins'];
        }

        return array_filter([
            [
                'svg' => 'id',
                'text' => $user['id']
            ],
            [
                'svg' => 'calendar',
                'text' => $this->time->toJoinedFormat($user['createdAt']),
                'title' => 'Joined'
            ],
            [
                'svg' => 'match',
                'text' => [
                    "{$wins}W",
                    "{$losses}L"
                ],
                'title' => 'Record'
            ],
            [
                'svg' => 'dollar-circle',
                'text' => '$' . number_format($winnings, 2),
                'title' => 'Winnings'
            ]
        ]);
    }
}
