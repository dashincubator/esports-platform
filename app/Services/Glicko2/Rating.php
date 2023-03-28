<?php

namespace App\Services\Glicko2;

class Rating
{

    private $glicko;

    private $rating;

    private $ratingDeviation;

    private $volatility;


    public function __construct(Glicko2 $glicko, float $rating, float $ratingDeviation, float $volatility)
    {
        $this->glicko = $glicko;
        $this->rating = $rating;
        $this->ratingDeviation = $ratingDeviation;
        $this->volatility = $volatility;
    }


    public function decay() : Rating
    {
        return $this->glicko->decay($this);
    }


    public function draw(Rating $opponent) : Rating
    {
        return $this->glicko->draw($this, $opponent);
    }


    public function getRating() : float
    {
        return $this->rating;
    }


    public function getRatingDeviation() : float
    {
        return $this->ratingDeviation;
    }


    public function getVolatility() : float
    {
        return $this->volatility;
    }


    // '$this' Player Lost The Match; Opponent Won
    public function lost(Rating $winner) : Rating
    {
        return $this->glicko->loss($this, $winner);
    }


    // '$this' Player Won The Match; Opponent Lost
    public function won(Rating $loser) : Rating
    {
        return $this->glicko->win($this, $loser);
    }
}
