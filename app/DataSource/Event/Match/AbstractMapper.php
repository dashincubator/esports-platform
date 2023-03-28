<?php

namespace App\DataSource\Event\Match;

use App\DataSource\{AbstractEntity, AbstractMapper as AbstractParent};

abstract class AbstractMapper extends AbstractParent
{

    // Used By Event\Team Delete Command
    // - Tournament Teams Can't Be Deleted Once Checked In
    // - Required For Ladder/League Teams
    public function findActiveMatchIdsByTeam(int $team) : array
    {
        return [];
    }


    public function findById(int $id) : AbstractEntity
    {
        return $this->findByIds($id)->get('id', $id, $this->row());
    }
}
