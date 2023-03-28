<?php

namespace App\DataSource\User\Lock\Message;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $content;

    protected $createdAt;

    protected $id;

    protected $user;


    protected function getCasts() : array
    {
        return [
            'content' => 'array'
        ];
    }


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
