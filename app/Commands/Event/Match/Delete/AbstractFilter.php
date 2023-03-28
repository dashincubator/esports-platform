<?php

namespace App\Commands\Event\Match\Delete;

use App\Commands\AbstractFilter as AbstractParent;

abstract class AbstractFilter extends AbstractParent
{

    protected function getSuccessMessage() : string
    {
        return 'Match(es) deleted successfully';
    }
}
