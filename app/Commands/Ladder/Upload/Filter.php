<?php

namespace App\Commands\Ladder\Upload;

use App\Commands\Upload\Image\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage() : string
    {
        return 'Ladder updated successfully!';
    }
}
