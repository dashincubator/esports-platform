<?php

namespace App\DataSource\Organization;

use App\DataSource\AbstractEntity;

class Entity extends AbstractEntity
{

    protected $guarded = [
        'createdAt',
        'id'
    ];


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function setDomain(string $domain) : string
    {
        $domain = array_reverse(explode('://', $domain, 2))[0];
        $domain = array_reverse(explode('www.', $domain, 2))[0];

        return $domain;
    }
}
