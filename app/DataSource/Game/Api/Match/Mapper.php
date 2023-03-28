<?php

namespace App\DataSource\Game\Api\Match;

use App\Commands\Game\Api\Match\Create\Command as CreateCommand;
use App\Commands\Game\Api\Match\Update\Command as UpdateCommand;
use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    private const EXPIRE = 2;

    private const REFRESH = 15;


    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'GameApiMatch';


    public function cachebust() : void
    { }


    // public function restoreDeletedHistory() : void
    // {
    //     $rows = $this->qb->select()
    //         ->table('DeleteHistory')
    //         ->where('name = ?', [$this->table])
    //         ->execute();
    //
    //     $rows = array_column($rows, 'data');
    //
    //     foreach ($rows as $index => $row) {
    //         $rows[$index] = $this->row(json_decode($row, true));
    //     }
    //
    //     $this->insert(...$rows);
    // }


    // Delete By Max(id) To Avoid Full DB Wipe On Api Issues
    public function deleteByApiAndUsernames(string $api, string ...$username) : Entities
    {
        if (!$username) {
            return $this->rows();
        }

        $join = $this->qb->select()
            ->fields('MIN(id) as id')
            ->groupBy('api, username')
            ->where("api = ? AND username IN {$this->qb->placeholders($username)}");

        $rows = $this->qb->select()
            ->table("({$join->getSQL(false)})", 'deleting')
            ->join("LEFT JOIN {$this->table} AS default ON default.id = deleting.id")
            ->where('', [$api, $username])
            ->execute();

        $rows = $this->rows($rows);

        $this->delete(...iterator_to_array($rows));

        return $rows;
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                if (!$entity->getId()) {
                    continue;
                }

                $pipe->hDel("FindById", $entity->getId());
            }
        });
    }


    public function existsByApiAndUsername(string $api, string $username) : bool
    {
        return $this->qb->select()
            ->where('api = ? AND username = ?', [$api, $username])
            ->count() > 0;
    }


    public function findByApiAndUsernames(string $api, int $endedAt, int $startedAt, string ...$username) : Entities
    {
        $keys = array_map(function($username) use ($api) {
            return "FindByApiAndUsernames:{$api}:{$username}";
        }, $username);

        $ids = $this->cache->mGet($keys, function (array $missing) use ($api, $endedAt, $startedAt) {
            $usernames = array_map(function($id) {
                return array_reverse(explode(':', $id))[0];
            }, $missing);

            $rows = $this->qb->select()
                ->fields('id', 'username')
                ->where("api = ? AND startedAt <= ? AND startedAt >= ? AND username IN {$this->qb->placeholders($usernames)}", [$api, $endedAt, $startedAt, $usernames])
                ->execute();

            $this->cache->pipeline(function($pipe) use ($api, $rows) {
                $buckets = [];

                foreach ($rows as $row) {
                    $buckets[$row['username']][] = $row['id'];
                }

                foreach ($buckets as $username => $ids) {
                    $pipe->set("FindLatestByApiAndUsernames:{$api}:{$username}", $ids, (self::EXPIRE * 60));
                }
            });

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    private function findByIds(int ...$ids) : Entities
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


    public function findAllByApiAndUsernames(string $api, string ...$usernames) : Entities 
    {
        $rows = $this->qb->select()
            ->fields('id')
            ->where("api = ? AND username IN {$this->qb->placeholders($usernames)}", [$api, $usernames])
            ->execute();;

        return $this->findByIds(...array_column($rows, 'id'));
    }


    public function findExpired(int $limit, int $page) : Entities
    {
        $expired = time() - (self::REFRESH * 60);
        $join = $this->qb->select()
            ->fields('api', 'createdAt', 'MAX(startedAt) as startedAt', 'username')
            ->groupBy('api, username')
            ->having("createdAt < {$expired}");

        $rows = $this->qb->select()
            ->table("({$join->getSQL(false)})", 'expired')
            ->join("LEFT JOIN {$this->table} AS default ON default.api = expired.api AND default.startedAt = expired.startedAt AND default.username = expired.username")
            ->orderBy('default.createdAt DESC')
            ->page($limit, $page)
            ->where("default.createdAt < {$expired}")
            ->execute();

        return $this->rows($rows);
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function scheduleCreateJob(array $args) : void
    {
        $this->scheduleLockedJob(CreateCommand::class, 'execute', $args);
    }


    public function scheduleUpdateJob() : void
    {
        $this->scheduleLockedJob(UpdateCommand::class, 'execute');
    }


    protected function updated(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }
}
