<?php

namespace App\Commands\Event\Team\Invite\Decline;

use App\Commands\Event\Team\Invite\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getSuccessMessage(string $action = '') : string
    {
        return parent::getSuccessMessage('declined');
    }
}
