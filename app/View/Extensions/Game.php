<?php

namespace App\View\Extensions;

use App\DataSource\Game\{Entities, Entity, Mapper};
use Contracts\Configuration\Configuration;
use Contracts\Support\Arrayable;
use Contracts\View\Extensions\Data;

class Game
{

    private $config;

    private $games;

    private $mapper;


    public function __construct(Configuration $config, Mapper $mapper)
    {
        $this->config = $config;
        $this->mapper = $mapper;
    }


    public function get(int $id) : Entity
    {
        return $this->getAll()->get('id', $id);
    }


    public function getAccountOptions() : array
    {
        return $this->config->get('game.accounts', []);
    }


    public function getAll() : Entities
    {
        if (is_null($this->games)) {
            $this->games = $this->mapper->findAll();
        }

        return $this->games;
    }


    public function getAllByPlatforms() : array
    {
        $groups = [];

        foreach ($this->getAll() as $game) {
            $groups[$game->getPlatform()][] = $game->toArray();
        }

        return $groups;
    }


    public function getAllBySlugs() : array
    {
        $groups = [];

        foreach ($this->getAll() as $game) {
            $groups[$game->getSlug()][] = $game->toArray();
        }

        ksort($groups);

        return $groups;
    }
    

    public function getAllSlugs() : array
    {
        $slugs = [];

        foreach ($this->getAll() as $game) {
            $slugs[] = $game->getSlug();
        }

        return array_unique(array_filter($slugs));
    }


    public function getAllStats() : array
    {
        $stats = array_values($this->getAllStatsBySlugs());
        $total = [];

        foreach ($stats as $stat) {
            foreach ($stat as $key => $count) {
                if (!isset($total[$key])) {
                    $total[$key] = 0;
                }

                $total[$key] += $count;
            }
        }

        return $total;
    }


    public function getAllStatsBySlugs() : array
    {
        $stats = [];

        foreach ($this->getAllBySlugs() as $slug => $games) {
            foreach ($games as $game) {
                $current = $this->toStatsArray($game);

                foreach ($current as $key => $count) {
                    if (!isset($stats[$slug][$key])) {
                        $stats[$slug][$key] = 0;
                    }

                    $stats[$slug][$key] += $count;
                }
            }
        }

        return $stats;
    }


    public function getViewOptions() : array
    {
        return $this->config->get('game.view', []);
    }


    private function toStatsArray(array $game) : array
    {
        return [
            'ladders' => $game['totalActiveLadders'],
            'leagues' => $game['totalActiveLeagues'],
            'matches' => $game['totalMatchesPlayed'],
            'tournaments' => $game['totalActiveTournaments'],
            'winnings' => ($game['totalPrizesPaid'] + $game['totalWagered'])
        ];
    }


    public function toTextListArray(Data $game) : array
    {
        return [
            [
                'svg' => 'ladder',
                'text' => number_format($game['totalActiveLadders']),
                'title' => 'Active Ladders'
            ],
            [
                'svg' => 'league',
                'text' => number_format($game['totalActiveLeagues']),
                'title' => 'Active Leagues'
            ],
            [
                'svg' => 'bracket',
                'text' => number_format($game['totalActiveTournaments']),
                'title' => 'Active Tournaments'
            ]/* ,
            [
                'svg' => 'match',
                'text' => number_format($game['totalMatchesPlayed']),
                'title' => 'Matches Played'
            ],
            [
                'svg' => 'dollar',
                'text' => '$' . number_format($game['totalPrizesPaid'] + $game['totalWagered']),
                'title' => 'Winnings Paid'
            ] */
        ];
    }
}
