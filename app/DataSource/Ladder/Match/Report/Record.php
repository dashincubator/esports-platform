<?php

namespace App\DataSource\Ladder\Match\Report;

use App\DataSource\Event\Match\Report\AbstractRecord;

class Record extends AbstractRecord
{

    protected $roster;


    protected function getCasts() : array
    {
        return [
            'roster' => 'array'
        ];
    }
}
