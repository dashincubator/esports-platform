<?php

namespace App\DataSource\User\Lock\Message;

use App\DataSource\AbstractEntity;

class Entity extends AbstractEntity
{

    protected $fillable = [
        'content', 'user'
    ];


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }
}
