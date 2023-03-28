<?php

/**
 *------------------------------------------------------------------------------
 *
 *  Glicko2 Ranking System - http://www.glicko.net/glicko.html
 *
 *  TODO: Rank Decay Cron Job
 *
 */

namespace App\Services\Glicko2;

class Glicko2
{

    private const DEFAULT_TAU = 0.5;

    private const DEFAULT_CONVERGENCE_TOLERANCE = 0.000001;

    private const RESULT_DRAW = 0.5;

    private const RESULT_LOSS = 0;
 
    private const RESULT_WIN = 1;


    private $factory;

    private $tau;

    private $tolerance;


    public function __construct(Factory $factory, float $tau = self::DEFAULT_TAU, float $tolerance = self::DEFAULT_CONVERGENCE_TOLERANCE)
    {
        $this->factory = $factory;
        $this->tau = $tau;
        $this->tolerance = $tolerance;
    }


    private function calculate(Rating $player1, Rating $player2, float $score) : Rating
    {
        $normalizedPlayer1 = $this->factory->createNormalizedRating($player1);
        $normalizedPlayer2 = $this->factory->createNormalizedRating($player2);

        $g = $this->g($normalizedPlayer2->getRatingDeviation());
        $e = $this->e($g, $normalizedPlayer1->getRating(), $normalizedPlayer2->getRating());

        $v = $this->v($e, $g);
        $t = $this->t($e, $g, $score, $v);

        $newVolatility = $this->calculateNewVolatility($normalizedPlayer1->getRatingDeviation(), $normalizedPlayer1->getVolatility(), $t, $v);
        $newRatingDeviation = $this->calculateNewRatingDeviation($normalizedPlayer1->getRatingDeviation(), $newVolatility, $v);
        $newRating = $this->calculateNewRating($normalizedPlayer1->getRating(), $e, $g, $newRatingDeviation, $score);

        return $this->factory->createStandardizedRating(
            $this->factory->createRating($newRating, $newRatingDeviation, $newVolatility)
        );
    }


    private function calculateNewRating(float $currentRating, float $e, float $g, float $newRatingDeviation, float $score) : float
    {
        return $currentRating + pow($newRatingDeviation, 2) * ($g * ($score - $e));
    }


    private function calculateNewRatingDeviation(float $currentRatingDeviation, float $newVolatility, float $v) : float
    {
        return 1 / sqrt((1 / pow($this->calculatePreRatingDeviation($currentRatingDeviation, $newVolatility), 2)) + (1 / $v));
    }


    private function calculateNewVolatility(float $currentRatingDeviation, float $currentVolatility, float $t, float $v) : float
    {
        $A = $a = log(pow($currentVolatility, 2));

        if (pow($t, 2) > (pow($currentRatingDeviation, 2) + $v)) {
            $B = log(pow($t, 2) - pow($currentRatingDeviation, 2) - $v);
        }
        elseif (pow($t, 2) <= (pow($currentRatingDeviation, 2) + $v)) {
            $k = 1;

            while ($this->f($a, $currentRatingDeviation, $t, $v, $a - ($k * $this->tau)) < 0) {
                $k++;
            }

            $B = $a - ($k * $this->tau);
        }

        $fA = $this->f($a, $currentRatingDeviation, $t, $v, $A);
        $fB = $this->f($a, $currentRatingDeviation, $t, $v, $B);

        while (abs($B - $A) > $this->tolerance) {
            $C = $A + ((($A - $B)*$fA)/($fB - $fA));
            $fC = $this->f($a, $currentRatingDeviation, $t, $v, $C);

            if (($fC * $fB) < 0) {
                $A = $B;
                $fA = $fB;
            }
            else {
                $fA = $fA / 2;
            }

            $B = $C;
            $fB = $fC;
        }

        return exp($A / 2);
    }


    private function calculatePreRatingDeviation(float $ratingDeviation, float $volatility) : float
    {
        return sqrt(pow($ratingDeviation, 2) + pow($volatility, 2));
    }


    public function decay(Rating $player) : Rating
    {
        $normalizedPlayer = $this->factory->createNormalizedRating($player);

        return $this->factory->createStandardizedRating(
            $this->factory->createRating(
                $normalizedPlayer->getRating(),
                $this->calculatePreRatingDeviation($normalizedPlayer->getRatingDeviation(), $normalizedPlayer->getVolatility()),
                $normalizedPlayer->getVolatility()
            )
        );
    }


    public function draw(Rating $player1, Rating $player2)  : Rating
    {
        return $this->calculate($player1, $player2, self::RESULT_DRAW);
    }


    private function e(float $g, float $player1Rating, float $player2Rating) : float
    {
        return 1 / (1 + exp(-$g * ($player1Rating - $player2Rating)));
    }


    private function f(float $a, float $ratingDeviation, float $t, float $v, float $x): float
    {
        return ((exp($x)*(pow($t, 2) - pow($ratingDeviation, 2) - $v - exp($x))) / (2*pow(pow($ratingDeviation, 2) + $v + exp($x), 2))) - (($x - $a) / $this->tau);
    }


    private function g(float $player2RatingDeviation) : float
    {
        return 1 / sqrt(1 + (3 * pow($player2RatingDeviation, 2))/pow(pi(), 2));
    }


    public function loss(Rating $loser, Rating $winner) : Rating
    {
        return $this->calculate($loser, $winner, self::RESULT_LOSS);
    }


    private function t(float $e, float $g, float $score, float $v) : float
    {
        return $v * ($g * ($score - $e));
    }


    private function v(float $e, float $g) : float
    {
        return pow(pow($g, 2) * $e * (1 - $e), -1);
    }


    public function win(Rating $winner, Rating $loser) : Rating
    {
        return $this->calculate($winner, $loser, self::RESULT_WIN);
    }
}
