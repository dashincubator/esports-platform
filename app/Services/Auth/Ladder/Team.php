<?php

namespace App\Services\Auth\Ladder;

use App\DataSource\Ladder\Team\Member\Mapper;
use App\Services\Auth\Event\AbstractTeam;

class Team extends AbstractTeam
{

    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
    }
}
