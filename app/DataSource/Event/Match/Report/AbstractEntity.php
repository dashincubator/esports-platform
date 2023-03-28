<?php

namespace App\DataSource\Event\Match\Report;

use App\DataSource\AbstractEntity as AbstractParent;

abstract class AbstractEntity extends AbstractParent
{

    public function inserting() : void
    {
        $this->set('createdAt', time());
    }


    public function placed(int $placement, int $user) : void
    {
        $this->set('user', $user);
        $this->set('placement', $placement);
        $this->set('reportedAt', time());
    }


    public function isReported() : bool
    {
        return $this->get('reportedAt') > 0;
    }


    public function toArray() : array
    {
        $data = parent::toArray();
        $data['isReported'] = $this->isReported();

        return $data;
    }
}
