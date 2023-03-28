<?php

namespace App\Commands\Ladder\Upload\Banner;

use App\Commands\Ladder\Upload\Filter;
use App\Commands\Upload\Image\AbstractCommand;
use Contracts\Upload\Image;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, Image $image)
    {
        parent::__construct($filter, $image);
    }
}
