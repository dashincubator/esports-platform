<?php

namespace App\DataSource\Organization;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'Organization';


    public function cachebust() : void
    {
        $this->cache->pipeline(function($pipe) {
            $pipe->delete("Find");
            $pipe->delete("FindByDomain");
            $pipe->delete("FindById");
        });
    }


    public function cachebustDomain(string $domain) : void
    {
        $this->cache->hDel("FindByDomain", $domain);
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            $pipe->delete("Find");

            foreach ($entities as $entity) {
                if (!$entity->getId()) {
                    continue;
                }

                $pipe->hDel("FindByDomain", $entity->getDomain());
                $pipe->hDel("FindById", $entity->getId());
            }
        });
    }


    public function findAll() : Entities
    {
        $ids = $this->cache->hGet("Find", "All", function() {
            $rows = $this->qb->select()
                ->fields('id')
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findByDomain(string $domain) : Entity
    {
        $id = $this->cache->hGet("FindByDomain", $domain, function(string $domain) {
            return $this->qb->select()
                ->fields('id')
                ->where('domain = ?', [$domain])
                ->first()['id'] ?? 0;
        });

        return $this->findById($id);
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


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function isUniqueDomain(string $domain, int $id = 0) : bool
    {
        $count = $this->qb->select()
            ->where('domain = ?', [$domain]);

        if ($id) {
            $count->where('AND id != ?', [$id]);
        }

        return $count->count() === 0;
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
