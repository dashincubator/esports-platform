<?php

namespace App\Commands\Game\Update;

use App\Commands\AbstractCommand;
use App\Commands\Game\Upload\{Banner\Command as BannerCommand, Card\Command as CardCommand};
use App\DataSource\Game\Mapper;
use Contracts\Upload\File;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(BannerCommand $banner, CardCommand $card, Filter $filter, Mapper $mapper)
    {
        $this->command = compact('banner', 'card');
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(
        ?string $account,
        ?File $banner,
        ?File $card,
        int $id,
        string $name,
        ?string $slug,
        string $view
    ) : bool
    {
        $game = $this->mapper->findById($id);

        if ($game->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $previous = $game->getSlug();

            if ($name !== $game->getName() || (is_string($slug) && $slug !== $game->getSlug())) {
                $game->fill(compact('name', 'slug'));

                if ($game->getSlug() !== $previous && !$this->mapper->isUniqueSlug($game->getPlatform(), $game->getSlug())) {
                    $this->filter->writeNameUnavailableMessage();
                }
            }

            if ($account === '') {
                $this->filter->writeGameAccountRequiredMessage();
            }

            if (!$this->filter->hasErrors()) {
                foreach (['banner', 'card'] as $key) {
                    if (!${$key}) {
                        continue;
                    }

                    ${$key} = $this->delegate($this->command[$key], [
                        'default' => $game->{'get' . ucfirst($key)}(),
                        'file' => ${$key},
                        'name' => $id
                    ]);
                }
            }
        }

        if (!$this->filter->hasErrors()) {
            $game->fill(compact($this->filter->getFields(['id'])));
            $this->mapper->update($game);
        }

        return $this->booleanResult();
    }
}
