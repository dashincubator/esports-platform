<?php

namespace App\DataSource\User\Bank\Transaction;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $amount;

    protected $createdAt;

    protected $fee = 0;

    protected $id;

    protected $ladder = 0;

    protected $ladderMatch = 0;

    protected $memo;

    protected $refundedAt = 0;

    protected $team = 0;

    protected $tournament = 0;

    protected $type;

    protected $user;


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
