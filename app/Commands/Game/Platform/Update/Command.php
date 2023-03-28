<?php

namespace App\Commands\Game\Platform\Update;

use App\Commands\AbstractCommand;
use App\DataSource\Game\Platform\Mapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, Mapper $mapper)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(?string $account, int $id, ?string $name, ?string $slug, ?string $view) : bool
    {
        $platform = $this->mapper->findById($id);

        if ($platform->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $previous = $platform->getSlug();

            if (($name && $name !== $platform->getName()) || ($slug && $slug !== $platform->getSlug())) {
                $platform->fill(compact('name', 'slug'));

                if ($previous !== $platform->getSlug() && !$this->mapper->isUniqueSlug($platform->getSlug())) {
                    $this->filter->writeNameUnavailableMessage();
                }
            }
        }

        if (!$this->filter->hasErrors()) {
            $platform->fill(compact($this->filter->getFields(['id'])));
            $this->mapper->update($platform);
        }

        return $this->booleanResult();
    }
}
