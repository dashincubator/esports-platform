<?php

namespace App\DataSource\Event\Match;

use App\DataSource\AbstractRecord as AbstractParent;

abstract class AbstractRecord extends AbstractParent
{

    protected $id;
 
    protected $startedAt = 0;

    protected $status = 0;

    protected $winningTeam = 0;


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
