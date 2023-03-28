<?php

namespace App\Commands\Organization\Create;

use App\Commands\Organization\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('created');
    }
}
