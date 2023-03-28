<?php

namespace App\Commands\Game\Upload;

use App\Commands\Upload\Image\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage() : string
    {
        return 'Game updated successfully!';
    }
}
