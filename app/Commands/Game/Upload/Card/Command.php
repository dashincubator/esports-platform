<?php

namespace App\Commands\Game\Upload\Card;

use App\Commands\Game\Upload\Filter;
use App\Commands\Upload\Image\AbstractCommand;
use Contracts\Upload\Image;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, Image $image)
    {
        parent::__construct($filter, $image);
    }
}
