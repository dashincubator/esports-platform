<?php

namespace App\DataSource\Event\Match\Report;

use App\DataSource\AbstractRecord as AbstractParent;

abstract class AbstractRecord extends AbstractParent
{

    protected $createdAt;

    protected $id;

    protected $match;

    protected $placement = 0;

    protected $reportedAt = 0;

    protected $team;

    protected $user = 0;


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
