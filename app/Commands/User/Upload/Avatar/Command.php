<?php

namespace App\Commands\User\Upload\Avatar;

use App\Commands\Upload\Image\AbstractCommand;
use App\Commands\User\Upload\Filter;
use Contracts\Upload\Image;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, Image $image)
    {
        parent::__construct($filter, $image);
    }
}
