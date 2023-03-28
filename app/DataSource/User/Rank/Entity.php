<?php

namespace App\DataSource\User\Rank;

use App\DataSource\AbstractEntity;
use App\Services\Glicko2\{Factory, Rating};

class Entity extends AbstractEntity
{

    protected $guarded = ['*'];


    public function __construct(Record $record, Factory $factory)
    {
        parent::__construct($record);

        if ($this->getScoreModifiedAt() === 0) {
            $this->updateGlicko2Rating($factory->createRating());
        }
    }


    public function addLoss(Rating $rating) : void
    {
        $this->increment('losses');
        $this->updateGlicko2Rating($rating);
    }


    public function addWin(Rating $rating) : void
    {
        $this->increment('wins');
        $this->updateGlicko2Rating($rating);
    }


    public function inserting() : void
    {
        parent::inserting();

        $this->set('rank', 0);
    }


    private function updateGlicko2Rating(Rating $rating) : void
    {
        $this->set('glicko2Rating', $rating->getRating());
        $this->set('glicko2RatingDeviation', $rating->getRatingDeviation());
        $this->set('glicko2Volatility', $rating->getVolatility());

        // Used By Redis To Create Rank
        $this->set('score', $rating->getRating());
        $this->set('scoreModifiedAt', time());
    }


    public function updating() : void
    {
        parent::updating();

        $this->set('rank', 0);
    }
}
