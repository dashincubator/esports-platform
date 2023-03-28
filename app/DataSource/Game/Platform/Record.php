<?php

namespace App\DataSource\Game\Platform;

use App\DataSource\AbstractRecord;

class Record extends AbstractRecord
{

    protected $account = '';

    protected $id;

    protected $name;

    protected $slug;

    protected $view = '';


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
