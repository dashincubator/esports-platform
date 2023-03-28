<?php

namespace App\Commands\App\Counters;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage() : string
    {
        return 'App counters updated successfully!';
    }
}
