<?php

namespace App\DataSource\Game\Api\Match;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $api;

    protected $createdAt = 0;

    protected $data = [];

    protected $endedAt = 0;

    protected $hash;

    protected $id;

    protected $startedAt = 0;

    protected $username;


    protected function getCasts() : array
    {
        return [
            'data' => 'array'
        ];
    }


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
