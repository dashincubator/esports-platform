<?php

namespace App\DataSource\User\Lock\Message\Template;

use App\DataSource\AbstractEntity;

class Entity extends AbstractEntity
{

    protected $fillable = [
        'content'
    ];


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }
}
