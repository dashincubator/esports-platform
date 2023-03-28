<?php

namespace App\Commands\Game\Create;

use App\Commands\AbstractCommand;
use App\DataSource\Game\Platform\Mapper as PlatformMapper;
use App\DataSource\Game\{Entity as GameEntity, Mapper as GameMapper};

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, GameMapper $game, PlatformMapper $platform)
    {
        $this->filter = $filter;
        $this->mapper = compact('game', 'platform');
    }


    protected function run(
        ?string $account,
        string $name,
        int $platform,
        ?string $slug,
        string $view
    ) : GameEntity
    {
        $platform = $this->mapper['platform']->findById($platform);

        if ($platform->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $game = $this->mapper['game']->create(array_merge(compact($this->filter->getFields(['account', 'platform'])), [
                'account' => ($account ? $account : $platform->getAccount()),
                'platform' => $platform->getId()
            ]));

            if (!$game->getAccount()) {
                $this->filter->writeGameAccountRequiredMessage();
            }

            if (!$this->mapper['game']->isUniqueSlug($game->getPlatform(), $game->getSlug())) {
                $this->filter->writeNameUnavailableMessage();
            }
        }

        if (!$this->filter->hasErrors()){
            $this->filter->writeSuccessMessage();
            $this->mapper['game']->insert($game);

            return $game;
        }

        return $this->mapper['game']->create();
    }
}
