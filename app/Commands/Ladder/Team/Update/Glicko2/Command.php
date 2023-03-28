<?php

namespace App\Commands\Ladder\Team\Update\Glicko2;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\Team\{Entity, Mapper};
use App\Services\Glicko2\{Factory, Glicko2 as Calculator, Rating};

class Command extends AbstractCommand
{

    private $factory;

    private $mapper;

    private $rating;


    public function __construct(Calculator $calculator, Factory $factory, Filter $filter, Mapper $mapper)
    {
        $this->factory = $factory;
        $this->filter = $filter;
        $this->mapper = $mapper;
        $this->rating = compact('calculator');
    }


    private function process(Entity $team, string $method, ?Rating $opponent) : void
    {
        if (is_null($opponent)) {
            return;
        }

        $team->{'add' . ucfirst($method)}(
            $this->rating['calculator']->{$method}(
                $this->factory->createRating(
                    $team->getRating(),
                    $team->getRatingGlicko2Deviation(),
                    $team->getRatingGlicko2Volatility()
                ),
                $opponent
            )
        );
    }


    /**
     *  '$ids' Should Be In Order Based On Placement
     *  - Array Keys = Placement
     *  - Rating Is Updated As Follows
     *
     *  $ids = [
     *      team1,     Beat Team 2
     *      team2,     Lost To Team 1, Beat Team 3
     *      team3,     Lost To Team 2, Beat Team 4
     *      team4      Lost To Team 3
     *  ];
     *
     *  @param array $ids @see Comments Above
     */
    public function run(array $ids) : void
    {
        $ratings = [];
        $teams = $this->mapper->findByIds(...$ids);

        foreach ($teams as $team) {
            $ratings[$team->getId()] = $this->factory->createRating(
                $team->getRating(),
                $team->getRatingGlicko2Deviation(),
                $team->getRatingGlicko2Volatility()
            );
        }

        foreach ($ids as $placement => $id) {
            $team = $teams->get('id', $id);

            $this->process($team, 'loss', ($ratings[$ids[$placement - 1] ?? null] ?? null));
            $this->process($team, 'win',  ($ratings[$ids[$placement + 1] ?? null] ?? null));
        }

        $this->mapper->update(...iterator_to_array($teams));
    }
}
