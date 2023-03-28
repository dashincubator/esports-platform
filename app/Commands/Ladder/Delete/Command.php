<?php

namespace App\Commands\Ladder\Delete;

use App\Commands\AbstractCommand;
use App\Commands\Ladder\Match\Delete\Command as DeleteMatchCommand;
use App\Commands\Ladder\Team\Delete\Command as DeleteTeamCommand;
use App\DataSource\Ladder\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(DeleteMatchCommand $match, DeleteTeamCommand $team, Filter $filter, Mapper $mapper)
    {
        $this->command = compact('match', 'team');
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    private function delete(Entity ...$ladders) : void
    {
        $this->mapper->delete(...$ladders);

        // Cascade
        foreach ($ladders as $ladder) {
            foreach (['match', 'team'] as $command) {
                $this->delegate($this->command[$command], [
                    'ladder' => $ladder->getId()
                ]);
            }
        }
    }


    private function deleteByGame(int $game) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findByGame($game))
        );
    }


    private function deleteById(int $id) : void
    {
        $ladder = $this->mapper->findById($id);

        if ($ladder->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->delete($ladder);
        }
    }


    private function deleteByOrganization(int $organization) : void
    {
        $this->delete(
            ...iterator_to_array($this->mapper->findAllByOrganization($organization))
        );
    }


    protected function run(?int $game, ?int $id, ?int $organization) : bool
    {
        if ($game) {
            $this->deleteByGame($game);
        }
        elseif ($id) {
            $this->deleteById($id);
        }
        elseif ($organization) {
            $this->deleteByOrganization($organization);
        }

        return $this->booleanResult();
    }
}
