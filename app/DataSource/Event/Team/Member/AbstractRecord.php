<?php

namespace App\DataSource\Event\Team\Member;

use App\DataSource\AbstractRecord as AbstractParent;

abstract class AbstractRecord extends AbstractParent
{

    protected $createdAt;

    protected $id;

    protected $managesMatches = false;

    protected $managesTeam = false;

    protected $status = 0;

    protected $team;

    protected $user;


    protected function getCasts() : array
    {
        return [
            'managesMatches' => 'bool',
            'managesTeam' => 'bool'
        ];
    }


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
