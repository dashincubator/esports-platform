<?php

namespace App\DataSource\User\Bank;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $balance = 0;

    protected $createdAt;

    protected $id;

    protected $organization;

    protected $user;

    protected $withdrawable = 0;


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
