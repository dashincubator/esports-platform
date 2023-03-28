<?php

namespace App\DataSource\User\Account;

use App\DataSource\AbstractEntity;

class Entity extends AbstractEntity
{

    protected $fillable = [
        'name', 'user', 'value'
    ];


    public function inserting() : void
    {
        $this->set('createdAt', time());
    }
}
