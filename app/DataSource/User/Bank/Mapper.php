<?php

namespace App\DataSource\User\Bank;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserBank';


    public function cachebust() : void
    {
        $this->cache->delete('FindById', 'FindByOrganizationAndUser');
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                if (!$entity->getId()) {
                    continue;
                }

                $pipe->hDel('FindById', $entity->getId());
                $pipe->hDel("FindByOrganizationAndUser:{$entity->getOrganization()}", $entity->getUser());
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

        $rows = $this->cache->hMGet('FindById', $ids, function(array $missing) {
            $rows = $this->qb->select()
                ->where("id IN {$this->qb->placeholders($missing)}", [$missing])
                ->execute();

            return array_combine(array_column($rows, 'id'), $rows);
        });

        return $this->rows($rows);
    }


    public function findByOrganizationAndUser(int $organization, int $user) : Entity
    {
        return $this->findByOrganizationAndUsers($organization, $user)->get('user', $user);
    }


    public function findByOrganizationAndUsers(int $organization, int ...$users) : Entities
    {
        if (!$users) {
            return $this->rows();
        }

        $ids = $this->cache->hMGet("FindByOrganizationAndUser:{$organization}", $users, function(array $missing) use ($organization) {
            $rows = $this->qb->select()
                ->fields('id', 'user')
                ->where("organization = ? AND user IN {$this->qb->placeholders($missing)}", [$organization, $missing])
                ->execute();

            $cache = [];

            foreach ($rows as $row) {
                $cache[$row['user']] = $row['id'];
            }

            return $cache;
        });

        $create = [];
        $rows = $this->findByIds(...$ids);

        foreach ($users as $user) {
            if ($rows->has('user', $user)) {
                $create[] = $rows->get('user', $user)->getStorageValues();
                continue;
            }

            $create[] = compact('organization', 'user');
        }

        return $this->rows($create);
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    protected function updated(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel('FindById', $entity->getId());
            }
        });
    }
}
