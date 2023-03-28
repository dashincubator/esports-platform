<?php

namespace App\DataSource\User\Lock\Message\Template;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $content;

    protected $createdAt;

    protected $id;


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
