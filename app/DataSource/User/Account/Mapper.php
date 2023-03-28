<?php

namespace App\DataSource\User\Account;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserAccount';


    public function cachebust(int ...$games) : void
    {
        $this->cache->delete("FindById", "FindByUser");
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("FindByNameAndUser:{$entity->getName()}", $entity->getUser());
                $pipe->hDel("FindByNameAndValue:{$entity->getName()}", $entity->getUser());
                $pipe->hDel("FindByUser", $entity->getUser());

                if ($entity->getId()) {
                    $pipe->hDel("FindById", $entity->getId());
                }
            }
        });
    }


    private function findById(int $id) : Entity
    {
        return $this->findByIds($id)->get('id', $id, $this->row());
    }


    private function findByIds(int ...$ids) : Entities
    {
        if (!$ids) {
            return $this->rows();
        }

        $rows = $this->cache->hMGet("FindById", $ids, function(array $missing) {
            $cache = [];
            $rows = $this->qb->select()
                ->where("id IN {$this->qb->placeholders($missing)}", [$missing])
                ->execute();

            foreach ($rows as $row) {
                $cache[$row['id']] = $row;
            }

            return $cache;
        });

        return $this->rows($rows);
    }


    public function findByNameAndUser(string $name, int $user) : Entities
    {
        $ids = $this->cache->hGet("FindByNameAndUser:{$name}", $user, function() use ($name, $user) {
            $row = $this->qb->select()
                ->fields('id')
                ->where('name = ? AND user = ?', [$name, $user])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findByNameAndUsers(string $name, int ...$user) : Entities
    {
        $ids = $this->cache->hMGet("FindByNameAndUser:{$name}", $user, function(array $missing) use ($name) {
            $cache = [];
            $rows = $this->qb->select()
                ->fields('id', 'user')
                ->where("name = ? AND user IN {$this->qb->placeholders($missing)}", [$name, $missing])
                ->execute();

            foreach ($rows as $row) {
                $cache[$row['user']] = $row['id'];
            }

            return $cache;
        });

        return $this->findByIds(...$ids);
    }


    public function findByNameAndValue(string $name, $value) : Entity
    {
        $id = $this->cache->hGet("FindByNameAndValue:{$name}", $value, function() use ($name, $value) {
            $row = $this->qb->select()
                ->fields('id')
                ->where('name = ? AND value = ?', [$name, $value])
                ->first();

            return $row['id'] ?? 0;
        });

        return $this->findById($id);
    }


    public function findByUser(int $user) : Entities
    {
        $ids = $this->cache->hGet("FindByUser", $user, function($user) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where('user = ?', [$user])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function isUnique(string $name, $value) : bool
    {
        $count = $this->qb->select()
            ->where("name = ? AND value = ?", [$name, $value])
            ->count();

        return $count === 0;
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
