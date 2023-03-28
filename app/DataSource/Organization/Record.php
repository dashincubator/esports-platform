<?php

namespace App\DataSource\Organization;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $createdAt;

    protected $domain;

    protected $id;

    protected $name;

    protected $paypal = '';

    protected $user = 0;


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
