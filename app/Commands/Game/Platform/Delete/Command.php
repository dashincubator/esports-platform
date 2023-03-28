<?php

namespace App\Commands\Game\Platform\Delete;

use App\Commands\AbstractCommand;
use App\Commands\Game\Delete\Command as DeleteGameCommand;
use App\DataSource\Game\Platform\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(DeleteGameCommand $game, Filter $filter, Mapper $mapper)
    {
        $this->command = compact('game');
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    private function delete(Entity ...$platforms) : void
    {
        $this->mapper->delete(...$platforms);

        foreach ($platforms as $platform) {
            $this->delegate($this->command['game'], [
                'platform' => $platform->getId()
            ]);
        }
    }


    private function deleteById(int $id) : void
    {
        $platform = $this->mapper->findById($id);

        if ($platform->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->delete($platform);
        }
    }


    private function deleteByIds(int ...$ids) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByIds(...$ids))
        );
    }


    protected function run(?int $id, ?array $ids) : bool
    {
        if ($id) {
            $this->deleteById($id);
        }
        elseif ($ids) {
            $this->deleteByIds(...$ids);
        }

        return $this->booleanResult();
    }
}
