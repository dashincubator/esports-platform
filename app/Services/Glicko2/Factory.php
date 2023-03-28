<?php

namespace App\Services\Glicko2;

use Contracts\Container\Container;

class Factory
{

    private const DEFAULT_RATING = 1500;

    private const DEFAULT_RATING_DEVIATION = 350;

    private const DEFAULT_VOLATILITY = 0.06;

    private const SCALE = 173.7178;


    private $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function createCompositeRating(Rating ...$players) : Rating
    {
        $rating = 0;
        $ratingDeviation = 0;
        $volatility = 0;

        foreach ($players as $player) {
            $rating += $player->getRating();
            $ratingDeviation += $player->getRatingDeviation();
            $volatility += $player->getVolatility();
        }

        $total = count($players);

        return $this->createRating(
            $rating / $total,
            $ratingDeviation / $total,
            $volatility / $total
        );
    }


    public function createNormalizedRating(Rating $rating) : Rating
    {
        return $this->createRating(
            (($rating->getRating() - self::DEFAULT_RATING) / self::SCALE),
            ($rating->getRatingDeviation() / self::SCALE),
            $rating->getVolatility()
        );
    }


    public function createRating(
        float $rating = self::DEFAULT_RATING,
        float $ratingDeviation = self::DEFAULT_RATING_DEVIATION,
        float $volatility = self::DEFAULT_VOLATILITY
    ) : Rating
    {
        return $this->container->resolve(Rating::class, [$rating, $ratingDeviation, $volatility]);
    }


    public function createStandardizedRating(Rating $rating) : Rating
    {
        return $this->createRating(
            (($rating->getRating() * self::SCALE) + self::DEFAULT_RATING),
            ($rating->getRatingDeviation() * self::SCALE),
            $rating->getVolatility()
        );
    }
}
