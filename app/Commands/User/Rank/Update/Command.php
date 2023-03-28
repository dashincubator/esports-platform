<?php

namespace App\Commands\User\Rank\Update;

use App\Commands\AbstractCommand;
use App\DataSource\User\Rank\{Entities as RankEntities, Mapper as RankMapper};
use App\Services\Glicko2\{Factory, Glicko2 as Calculator};

class Command extends AbstractCommand
{

    private $factory;

    private $rating;

    private $mapper;


    public function __construct(Calculator $calculator, Factory $factory, Filter $filter, RankMapper $mapper)
    {
        $this->factory = $factory;
        $this->filter = $filter;
        $this->mapper = $mapper;
        $this->rating = compact('calculator', 'factory');
    }


    private function process(RankEntities $ranks, string $method, ?array $opponents, array $originalRatings, array $roster) : void
    {
        if (is_null($opponents)) {
            return;
        }

        foreach ($opponents as $index => $user) {
            $opponents[$index] = $originalRatings[$user];
        }

        $composite = $this->rating['factory']->createCompositeRating(...$opponents);

        foreach ($roster as $user) {
            $rank = $ranks->get('user', $user);
            $rank->{'add' . ucfirst($method)}(
                $this->rating['calculator']->{$method}(
                    $this->factory->createRating(
                        $user->getRating(),
                        $user->getRatingGlicko2Deviation(),
                        $user->getRatingGlicko2Volatility()
                    ),
                    $composite
                )
            );
        }
    }


    /**
     *  '$rosters' Should Be In Order Based On Placement
     *  - Array Keys = Placement
     *  - Rating Is Updated As Follows
     *
     *  $rosters = [
     *      [team1 roster],     Beat Team 2
     *      [team2 roster],     Lost To Team 1, Beat Team 3
     *      [team3 roster],     Lost To Team 2, Beat Team 4
     *      [team4 roster]      Lost To Team 3
     *  ];
     *
     *  @param int $game
     *  @param array $rosters @see Comments Above
     */
    public function run(int $game, array $rosters) : void
    {
        $originalRatings = [];
        $ranks = $this->mapper->findByGameAndUsers($game, ...array_merge(...$rosters));

        // Teams Win/Lose Against Ratings Before Match Began
        foreach ($ranks as $rank) {
            $originalRatings[$rank->getUser()] = $this->factory->createRating(
                $rank->getRating(),
                $rank->getRatingGlicko2Deviation(),
                $rank->getRatingGlicko2Volatility()
            );
        }

        foreach ($rosters as $placement => $roster) {
            $this->process($ranks, 'loss', ($rosters[$placement - 1] ?? null), $originalRatings, $roster);
            $this->process($ranks, 'win', ($rosters[$placement + 1] ?? null), $originalRatings, $roster);
        }

        $insert = [];
        $update = [];

        foreach ($ranks as $rank) {
            if (!$rank->getId()) {
                $insert[] = $rank;
            }
            else {
                $update[] = $rank;
            }
        }

        $this->mapper->insert(...$insert);
        $this->mapper->update(...$update);
    }
}
