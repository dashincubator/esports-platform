<?php

namespace App\Commands\Ladder\Gametype\Create;

use App\Commands\Ladder\Gametype\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('created');
    }
}
