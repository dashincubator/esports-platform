<?php

namespace App\DataSource\Ladder\Team;

use App\DataSource\Event\Team\AbstractEntity;
use App\Services\Glicko2\{Factory, Rating};
use Contracts\Slug\Generator;

class Entity extends AbstractEntity
{

    public function __construct(Record $record, Factory $factory, Generator $slug)
    {
        parent::__construct($record, $slug);

        if ($this->getScoreModifiedAt() === 0) {
            $this->updateGlicko2Rating($factory->createRating());
        }
    }


    public function addLoss(Rating $rating) : void
    {
        $this->increment('losses');
        $this->updateGlicko2Rating($rating);
        $this->updateScore($rating->getRating());
    }


    public function addWin(Rating $rating) : void
    {
        $this->increment('wins');
        $this->updateGlicko2Rating($rating);
        $this->updateScore($rating->getRating());
    }


    public function getEventId() : int
    {
        return $this->get('ladder');
    }


    public function inserting() : void
    {
        parent::inserting();

        $this->set('rank', 0);
    }


    public function joined(int $ladder) : void
    {
        $this->set('ladder', $ladder);
    }


    public function updateGameStatsScore(int $score) : bool
    {
        // Most Likely Have An Issue With The Game API
        if ($score < 1) {
            return false;
        }

        $this->updateScore($score);

        return true;
    }


    private function updateGlicko2Rating(Rating $rating) : void
    {
        $this->set('glicko2Rating', $rating->getRating());
        $this->set('glicko2RatingDeviation', $rating->getRatingDeviation());
        $this->set('glicko2Volatility', $rating->getVolatility());
    }


    // Used By Redis To Determine Leaderboard Placement
    private function updateScore(int $score) : void
    {
        $this->set('score', $score);
        $this->set('scoreModifiedAt', time());
    }


    public function updating() : void
    {
        parent::updating();

        $this->set('rank', 0);
    }
}
