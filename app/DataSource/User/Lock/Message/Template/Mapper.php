<?php

namespace App\DataSource\User\Lock\Message\Template;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserLockMessageTemplate';


    public function cachebust() : void
    {
        $this->cache->delete("FindById");
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->delete("FindById", $entity->getId());
            }
        });
    }


    public function findById(int $id) : Entity
    {
        if (!$id) {
            return $this->row();
        }

        $row = $this->cache->hGet("FindById", $id, function() use ($id) {
            return $this->qb->select()
                ->where("id = ?", [$id])
                ->first();
        });

        return $this->row($row);
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    protected function updated(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }
}
