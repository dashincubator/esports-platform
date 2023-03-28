<?php

namespace App\DataSource\User\ForgotPassword;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $code;

    protected $createdAt;

    protected $emailedAt = 0;

    protected $id;

    protected $user;


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
