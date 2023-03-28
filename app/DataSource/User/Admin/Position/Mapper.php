<?php

namespace App\DataSource\User\Admin\Position;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserAdminPosition';


    public function cachebust() : void
    {
        $this->cache->delete("FindAll", "FindById");
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            $pipe->delete("FindAll");

            foreach ($entities as $entity) {
                $pipe->hDel("FindById", $entity->getId());
            }
        });
    }


    public function findAll() : Entities
    {
        $ids = $this->cache->hGetAll("FindAll", function() {
            $rows = $this->qb->select()
                ->fields('id')
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findById(int $id) : Entity
    {
        return $this->findByIds($id)->get('id', $id, $this->row());
    }


    public function findByIds(int ...$ids) : Entities
    {
        if (!$ids) {
            return $this->rows();
        }

        $rows = $this->cache->hMGet("FindById", $ids, function(array $missing) {
            $rows = $this->qb->select()
                ->where("id IN {$this->qb->placeholders($missing)}", [$missing])
                ->execute();

            return array_combine(array_column($rows, 'id'), $rows);
        });

        return $this->rows($rows);
    }


    public function findByOrganization(int $organization) : Entities
    {
        $rows = $this->qb->select()
            ->where("organization = ?", [$organization])
            ->execute();

        return $this->rows($rows);
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    protected function updated(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("FindById", $entity->getId());
            }
        });
    }
}
