<?php

namespace App\DataSource\User\Lock\Message;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserLockMessage';


    public function cachebust() : void
    {
        $this->cache->delete(
            "FindById",
            "FindLatestByUser"
        );
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->delete("FindById", $entity->getId());
                $pipe->delete("FindLatestByUser:{$entity->getUser()}");
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


    public function findLatestByUser(int $user) : Entity
    {
        $key = "FindLatestByUser:{$user}";
        $id = $this->cache->get($key, function() use ($key, $user) {
            $id = $this->qb->select()
                ->fields('id')
                ->orderBy('createdAt DESC')
                ->where('user = ?', [$user])
                ->first()['id'] ?? 0;

            $this->cache->set($key, $id, (5 * 60));

            return $id;
        });

        return $this->findById($id);
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
