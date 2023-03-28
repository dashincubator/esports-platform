<?php

namespace App\DataSource\Ladder\Team\Member;

use App\DataSource\Event\Team\Member\AbstractRecord;

class Record extends AbstractRecord
{

    protected $account = '';

    protected $score = 0;

    protected $scoreModifiedAt = 0;
}
