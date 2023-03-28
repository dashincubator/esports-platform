<?php

namespace App\View\Extensions;

use App\Organization;
use App\DataSource\Ladder\{Entities, Entity, Mapper};
use App\Services\Api\Managers;
use Contracts\Configuration\Configuration;
use Contracts\Support\Arrayable;
use Contracts\View\Extensions\Data;

class Ladder implements Arrayable
{

    private $ladders;

    private $managers;

    private $mapper;

    private $organization;

    private $time;


    public function __construct(Managers $managers, Mapper $mapper, Organization $organization, Time $time)
    {
        $this->managers = $managers;
        $this->mapper = $mapper;
        $this->organization = $organization;
        $this->time = $time;
    }


    public function filterByGame(int $game) : Entities
    {
        return $this->getAll()->filter(function(Entity $entity) use ($game) {
            return $entity->getGame() === $game;
        });
    }


    public function filterByGameAndLadders(int $game) : Entities
    {
        return $this->filterByGame($game)->filter(function(Entity $entity) {
            return $entity->isLadder();
        });
    }


    public function filterByGameAndLeagues(int $game) : Entities
    {
        return $this->filterByGame($game)->filter(function(Entity $entity) {
            return $entity->isLeague();
        });
    }


    public function filterByLadders() : Entities
    {
        return $this->getAll()->filter(function(Entity $entity) {
            return $entity->isLadder();
        });
    }


    public function filterByLeagues() : Entities
    {
        return $this->getAll()->filter(function(Entity $entity) {
            return $entity->isLeague();
        });
    }


    public function has(int $id) : bool
    {
        return $this->getAll()->has('id', $id);
    }


    public function get(int $id) : Entity
    {
        return $this->getAll()->get('id', $id);
    }


    private function getAll() : Entities
    {
        if (is_null($this->ladders)) {
            $this->ladders = $this->mapper->findAllByOrganization($this->organization->getId() ?? 0);
        }

        return $this->ladders;
    }


    public function getFormatOptions() : array
    {
        $default = [
            '' => 'Use Default Matchfinder'
        ];

        $format = $this->managers->getCompetitionFormatOptions();
        $format = array_combine($format, $format);

        return array_merge($default, $format);
    }


    public function groupBySlug() : array
    {
        $data = [];

        foreach ($this->getAll() as $ladder) {
            $data[$ladder->getSlug()][] = $ladder;
        }

        return $data;
    }


    public function toArray() : array
    {
        return $this->getAll()->toArray();
    }


    public function toEntryFeeText(string $fee) : string
    {
        if ($fee > 0) {
            return number_format($fee) . ' Per Player';
        }

        return 'Free Entry';
    }


    public function toHomepageRowFormat(Data $ladder) : array
    {
        return [
            'entry' => (($ladder['entryFee'] > 0)
                ? number_format($ladder['entryFee'], 0) . ' Per Player'
                : 'Free Entry'),

            'start' => $this->time->toLadderFormat($ladder['startsAt']),
            'ends' => $this->time->toLadderFormat($ladder['endsAt']),
        ];
    }


    public function toTextListArray(Data $ladder) : array
    {
        return array_filter([
            [
                'svg' => 'dollar',
                'text' => ($ladder['entryFee'] > 0)
                    ? number_format($ladder['entryFee'], 0) . ' Per Player'
                    : 'Free Entry',
                'title' => 'Entry Fee'
            ],
            ($ladder['membershipRequired'] ? [
                'svg' => 'membership',
                'text' => 'Membership Required'
            ] : []),
            ($ladder['stopLoss'] ? [
                'svg' => 'stop',
                'text' => $ladder['stopLoss'],
                'title' => 'Stop Loss'
            ] : []),
            [
                'svg' => 'calendar',
                'text' => $this->time->toLadderFormat($ladder['startsAt']),
                'title' => ucfirst($ladder['type']) .' Play Starts'
            ],
            [
                'svg' => 'calendar-end',
                'text' => $this->time->toLadderFormat($ladder['endsAt']),
                'title' => ucfirst($ladder['type']) .' Ends'
            ],
            [
                'svg' => 'team',
                'text' => $ladder['totalRegisteredTeams'],
                'title' => 'Registered Teams'
            ],
            [
                'svg' => 'match',
                'text' => $ladder['totalMatchesPlayed'],
                'title' => 'Matches Played'
            ],
            ($ladder['totalWagered'] ? [
                'svg' => 'dollar',
                'text' => '$' . number_format($ladder['totalWagered']),
                'title' => 'Wagered'
            ] : [])
        ]);
    }


    public function toTextListArrayShort(Data $ladder) : array
    {
        $items = $this->toTextListArray($ladder);

        foreach ($items as $index => $item) {
            if (in_array($item['text'], ['Matches Played', 'Membership Required', 'Prizes', 'Registered Teams'])) {
                unset($items[$index]);
            }
        }

        return $items;
    }
}
