<?php

namespace App\Commands\Event\Team\Update;

use App\Commands\AbstractCommand as AbstractParent;
use App\Commands\Upload\Image\AbstractCommand as AvatarCommand;
use App\Commands\Upload\Image\AbstractCommand as BannerCommand;
use App\DataSource\Event\Team\AbstractMapper as TeamMapper;
use Contracts\Upload\File;

abstract class AbstractCommand extends AbstractParent
{

    private $command;

    private $mapper;


    public function __construct(AvatarCommand $avatar, BannerCommand $banner, Filter $filter, TeamMapper $mapper)
    {
        $this->command = compact('avatar', 'banner');
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(?File $avatar, ?File $banner, ?string $bio, int $id) : bool
    {
        $team = $this->mapper->findById($id);

        if ($team->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            foreach (['avatar', 'banner'] as $key) {
                if (!${$key}) {
                    continue;
                }

                ${$key} = $this->delegate($this->command[$key], [
                    'default' => $team->{'get' . ucfirst($key)}(),
                    'file' => ${$key},
                    'name' => $team->getId()
                ]);
            }

            $team->fill(compact($this->filter->getFields(['id'])));
        }

        if (!$this->filter->hasErrors()) {
            $this->mapper->update($team);
        }

        return $this->booleanResult();
    }
}
