<?php

namespace App\DataSource\User\Account;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $createdAt;

    protected $id;

    protected $name;

    protected $user;

    protected $value;


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
