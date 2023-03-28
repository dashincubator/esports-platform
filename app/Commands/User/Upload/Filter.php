<?php

namespace App\Commands\User\Upload;

use App\Commands\Upload\Image\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage() : string
    {
        return 'Account updated successfully!';
    }
}
