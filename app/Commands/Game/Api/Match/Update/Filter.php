<?php

namespace App\Commands\Game\Api\Match\Update;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage() : string
    {
        return "Game stat(s) updated successfully!";
    }
}
