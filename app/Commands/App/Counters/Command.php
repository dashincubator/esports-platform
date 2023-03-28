<?php

// TODO: Replace With Something Better

namespace App\Commands\App\Counters;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\{Entities as LadderEntities, Mapper as LadderMapper};
use App\DataSource\Game\{Entity as GameEntity, Mapper as GameMapper};

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, GameMapper $game, LadderMapper $ladder)
    {
        $this->filter = $filter;
        $this->mapper = compact('game', 'ladder');
    }


    protected function run() : bool
    {
        // $games = $this->mapper['game']->findAll();
        // $ladders = $this->mapper['ladder']->findAll();
        //
        // $this->updateLadders($ladders);
        //
        // foreach ($games as $game) {
        //     $this->updateGame(
        //         $game,
        //         $ladders->filter(function($ladder) use ($game) {
        //             return $ladder->getGame() === $game->getId();
        //         })
        //     );
        // }
        //
        // $this->mapper['game']->update(...iterator_to_array($games));
        // $this->mapper['ladder']->update(...iterator_to_array($ladders));

        $this->filter->writeSuccessMessage();

        return $this->booleanResult();
    }


    private function updateGame(GameEntity $game, LadderEntities $ladders) : void
    {
        $totalPrizesPaid = 0;

        while ($amount = $this->mapper['game']->popTotalPrizesPaidQueue($game->getId())) {
            $totalPrizesPaid += $amount;
        }

        $game->updateCounters(
            count($ladders->filter(function($ladder) use ($game) {
                return $ladder->isLadder();
            })),
            count($ladders->filter(function($ladder) use ($game) {
                return $ladder->isLeague();
            })),
            0,
            array_sum($ladders->column('totalMatchesPlayed')),
            $totalPrizesPaid,
            array_sum($ladders->column('totalWagered'))
        );
    }


    private function updateLadders(LadderEntities $ladders) : void
    {
        foreach ($ladders as $ladder) {
            while ($this->mapper['ladder']->popTotalMatchesPlayedQueue($ladder->getId())) {
                $ladder->increment('totalMatchesPlayed');
            }

            while ($amount = $this->mapper['ladder']->popTotalWageredQueue($ladder->getId())) {
                $ladder->increment('totalWagered', $amount ?? 0);
            }
        }
    }
}
