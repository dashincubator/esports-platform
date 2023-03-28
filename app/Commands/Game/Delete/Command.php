<?php

namespace App\Commands\Game\Delete;

use App\Commands\AbstractCommand;
use App\Commands\Ladder\Delete\Command as DeleteLadderCommand;
use App\DataSource\Game\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(DeleteLadderCommand $ladder, Filter $filter, Mapper $mapper)
    {
        $this->command = $ladder;
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    private function delete(Entity ...$games) : void
    {
        $this->mapper->delete(...$games);

        foreach ($games as $game) {
            $this->delegate($this->command, [ 'game' => $game->getId() ]);
        }
    }


    private function deleteById(int $id) : void
    {
        $game = $this->mapper->findById($id);

        if ($game->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->delete($game);
        }
    }


    private function deleteByIds(int ...$ids) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByIds(...$ids))
        );
    }


    private function deleteByPlatform(int $platform) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByPlatform($platform))
        );
    }


    protected function run(?int $id, ?array $ids, ?int $platform) : bool
    {
        if ($id) {
            $this->deleteById($id);
        }
        elseif ($ids) {
            $this->deleteByIds(...$ids);
        }
        elseif ($platform) {
            $this->deleteByPlatform($platform);
        }

        return $this->booleanResult();
    }
}
