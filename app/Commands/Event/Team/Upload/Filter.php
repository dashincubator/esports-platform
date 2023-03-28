<?php

namespace App\Commands\Event\Team\Upload;

use App\Commands\Upload\Image\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage() : string
    {
        return 'Team updated successfully!';
    }
}
