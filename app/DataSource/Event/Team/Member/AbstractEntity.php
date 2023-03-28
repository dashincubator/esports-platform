<?php

namespace App\DataSource\Event\Team\Member;

use App\DataSource\AbstractEntity as AbstractParent;

abstract class AbstractEntity extends AbstractParent
{

    protected $fillable = [
        'managesMatches',
        'managesTeam',
        'team',
        'user'
    ];


    public function accept() : void
    {
        $this->set('status', 1);
    }


    public function founder() : void
    {
        $this->set('managesMatches', true);
        $this->set('managesTeam', true);
        $this->set('status', 1);
    }


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function invite() : void
    {
        $this->set('status', 0);
    }


    public function isInvite() : bool
    {
        return $this->get('status') === 0;
    }


    public function managesMatches() : bool
    {
        return $this->get('managesMatches');
    }


    public function managesTeam() : bool
    {
        return $this->get('managesTeam');
    }
}
