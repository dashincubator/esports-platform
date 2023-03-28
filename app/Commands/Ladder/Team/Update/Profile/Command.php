<?php

namespace App\Commands\Ladder\Team\Update\Profile;

use App\Commands\Ladder\Team\Upload\Avatar\Command as AvatarCommand;
use App\Commands\Ladder\Team\Upload\Banner\Command as BannerCommand;
use App\Commands\Event\Team\Update\{AbstractCommand, Filter};
use App\DataSource\Ladder\Team\Mapper;

class Command extends AbstractCommand
{

    public function __construct(AvatarCommand $avatar, BannerCommand $banner, Filter $filter, Mapper $mapper)
    {
        parent::__construct($avatar, $banner, $filter, $mapper);
    }
}
