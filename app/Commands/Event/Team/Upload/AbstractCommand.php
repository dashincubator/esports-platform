<?php

namespace App\Commands\Event\Team\Upload;

use App\Commands\Upload\Image\AbstractCommand as AbstractParent;
use Contracts\Upload\Image;

abstract class AbstractCommand extends AbstractParent
{

    public function __construct(Filter $filter, Image $image)
    {
        parent::__construct($filter, $image);
    }
}
