<?php

namespace App\DataSource\Event\Match;

use App\DataSource\AbstractEntity as AbstractParent;

abstract class AbstractEntity extends AbstractParent
{

    protected $guarded = [
        'id',
        'startedAt',
        'status',
        'winningTeam'
    ]; 


    public function isReportable() : bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        return ($this->isActive() || $this->isDisputed());
    }


    public function toArray() : array
    {
        $data = parent::toArray();

        foreach (['isActive', 'isComplete', 'isDisputed', 'isReportable', 'isUpcoming'] as $method) {
            $data[$method] = $this->{$method}();
        }

        return $data;
    }
}
