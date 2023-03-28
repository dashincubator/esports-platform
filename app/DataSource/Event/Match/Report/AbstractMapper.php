<?php

namespace App\DataSource\Event\Match\Report;

use App\DataSource\{AbstractEntity, AbstractMapper as AbstractParent};

abstract class AbstractMapper extends AbstractParent
{

    public function findById(int $id) : AbstractEntity
    {
        return $this->findByIds($id)->get('id', $id, $this->row());
    }
}
