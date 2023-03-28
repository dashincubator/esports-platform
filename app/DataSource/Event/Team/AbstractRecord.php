<?php

namespace App\DataSource\Event\Team;

use App\DataSource\AbstractRecord as AbstractParent;

abstract class AbstractRecord extends AbstractParent
{

    protected $avatar = '';

    protected $banner = '';

    protected $bio = '';

    protected $createdAt;

    protected $createdBy;

    protected $id;

    protected $locked = false;

    protected $lockedAt = 0;

    protected $losses = 0;

    protected $name = '';

    protected $slug = '';

    protected $wins = 0;


    protected function getCasts() : array
    {
        return [
            'locked' => 'bool'
        ];
    }


    public function getPrimaryField() : string
    {
        return 'id';
    }
}
