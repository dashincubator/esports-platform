<?php

namespace App\DataSource\User;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    private const EXPIRE_COUNTERS = 2;


    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'User';


    public function cachebust() : void
    {
        $this->cache->delete(
            "FindAllAdmin",
            "FindById",
            "FindByIdentifier",
            "FindBySlug"
        );
    }


    public function cachebustId(int $id)
    {
        $this->cache->hDel("FindById", $id);
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("FindById", $entity->getId());
                $pipe->hDel("FindByIdentifier", $entity->getEmail(), $entity->getUsername());
                $pipe->hDel("FindBySlug", $entity->getSlug());

                if ($entity->cachebustAdmin()) {
                    $this->cache->delete("FindAllAdmin");
                }
            }
        });
    }


    public function findAllAdmin() : Entities
    {
        $ids = $this->cache->hGetAll("FindAllAdmin", function() {
            $rows = $this->qb->select()
                ->fields('id')
                ->where('adminPosition > 0')
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findBy(string $column, string $value) : Entity
    {
        $row = $this->qb->select()
            ->where("{$column} = ?", [$value])
            ->first();

        return $this->row($row);
    }


    public function findByEmail(string $email) : Entity
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->findByIdentifier($email);
        }

        return $this->row();
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


    public function findByIdentifier(string $identifier) : Entity
    {
        $id = $this->cache->hGet("FindByIdentifier", $identifier, function(string $identifier) {
            return $this->qb->select()
                ->fields('id')
                ->where('email = ? OR username = ?', [$identifier, $identifier])
                ->first()['id'] ?? 0;
        });

        return $this->findById($id);
    }


    public function findBySlug(string $slug) : Entity
    {
        $id = $this->cache->hGet('FindBySlug', $slug, function() use ($slug) {
            return $this->qb->select()
                ->fields('id')
                ->where('slug = ?', [$slug])
                ->first()['id'] ?? 0;
        });

        return $this->findById($id);
    }


    public function findCounters() : array
    {
        $counters = $this->cache->get("FindCounters", function() {
            $counters = $this->qb->select()
                ->fields(
                    'COUNT(*) as users',
                    'SUM(createdAt > ' . (time() - (60 * 60 * 24)) . ') as registered24h',
                    'SUM(membershipExpiresAt > ' . time() . ') as memberships'
                )
                ->first();

            $this->cache->set("FindCounters", $counters, (self::EXPIRE_COUNTERS * 60));

            return $counters;
        });
        $data = [
            'Registered Users' => 'users',
            'Registered Past 24H' => 'registered24h',
            'Total Users With Memberships' => 'memberships'
        ];

        foreach ($data as $name => $key) {
            $data[$name] = $counters[$key] ?? 0;
        }

        return $data;
    }


    public function findIdsByAdminPositions(int ...$positions) : array
    {
        if (!$positions) {
            return [];
        }

        $rows = $this->qb->select()
            ->fields('id')
            ->where("adminPosition IN {$this->qb->placeholders($positions)}", [$positions])
            ->execute();

        return array_column($rows, 'id');
    }


    public function isUniqueEmail(string $email) : bool
    {
        $count = $this->qb->select()
            ->where('email = ?', [$email])
            ->count();

        return $count === 0;
    }


    public function isUniqueSlugAndUsername(string $slug, string $username) : bool
    {
        $count = $this->qb->select()
            ->where('slug = ? OR username = ?', [$slug, $username])
            ->count();

        return $count === 0;
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
