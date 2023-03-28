<?php

namespace App\Commands\Ladder\Team\Upload\Banner;

use App\Commands\Event\Team\Upload\{AbstractCommand, Filter};
use Contracts\Upload\Image;

class Command extends AbstractCommand
{

    public function __construct(Filter $filter, Image $image)
    {
        parent::__construct($filter, $image);
    }
}
