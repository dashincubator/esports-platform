<?php

namespace App\DataSource\Ladder\Team\Member;

use App\DataSource\AbstractEntity;
use App\DataSource\Event\Team\Member\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'LadderTeamMember';


    // Member\AbstractMapper
    protected $foreignTable = 'LadderTeam';
    protected $foreignKey = 'ladder';


    public function findByLadderAndUser(int $ladder, int $user) : AbstractEntity
    {
        return $this->findByEventAndUser($ladder, $user);
    }
}
