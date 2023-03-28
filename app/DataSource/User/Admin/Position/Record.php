<?php

namespace App\DataSource\User\Admin\Position;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $createdAt;

    protected $games = [];

    protected $id;

    protected $name;

    protected $organization;

    protected $permissions = [];


    protected function getCasts() : array
    {
        return [
            'games' => 'array',
            'permissions' => 'array'
        ];
    }


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
