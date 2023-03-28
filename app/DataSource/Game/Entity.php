<?php

namespace App\DataSource\Game;

use App\DataSource\AbstractEntity;
use Contracts\Slug\Generator;

class Entity extends AbstractEntity
{

    private $slug;


    protected $fillable = [
        'account', 'banner', 'card', 'name',
        'platform', 'slug', 'view'
    ];


    public function __construct(Generator $slug, Record $record)
    {
        parent::__construct($record);

        $this->slug = $slug;
    }


    protected function setName(string $name) : string
    {
        $this->set('slug', $name);

        return $name;
    }


    protected function setSlug(string $slugify) : string
    {
        if (!$slugify) {
            return $this->get('slug');
        }

        return $this->slug->generate($slugify);
    }


    public function updateCounters(
        int $totalActiveLadders,
        int $totalActiveLeagues,
        int $totalActiveTournaments,
        int $totalMatchesPlayed,
        float $totalPrizesPaid,
        float $totalWagered
    ) : void
    {
        $this->set('totalActiveLadders', $totalActiveLadders);
        $this->set('totalActiveLeagues', $totalActiveLeagues);
        $this->set('totalActiveTournaments', $totalActiveTournaments);
        $this->increment('totalMatchesPlayed', $totalMatchesPlayed);
        $this->increment('totalPrizesPaid', $totalPrizesPaid);
        $this->increment('totalWagered', $totalWagered);
    }
}
