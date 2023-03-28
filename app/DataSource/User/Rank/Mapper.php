<?php

namespace App\DataSource\User\Rank;

use App\DataSource\AbstractMapper;
use App\Commands\User\Rank\Update\Command as UpdateCommand;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserRank';


    public function cachebust(int ...$games) : void
    {
        $this->cache->pipeline(function($pipe) use ($games) {
            $pipe->delete("FindById", "FindByUser");

            foreach ($games as $game) {
                $pipe->delete("{$game}");
            }
        });

        $this->cachebustLeaderboards(...$games);
    }


    public function cachebustLeaderboards(int ...$games) : void
    {
        $this->cache->pipeline(function($pipe) use ($games) {
            foreach ($games as $game) {
                $pipe->delete("{$game}:LeaderboardByEarnings");
                $pipe->delete("{$game}:LeaderboardByScores");
            }

            $rows = $this->qb->select()
                ->fields('earnings', 'game', 'score')
                ->flags('distinct')
                ->where("game IN {$this->qb->placeholders($games)}", [$games])
                ->execute();

            foreach ($rows as $row) {
                $pipe->zAdd("{$row['game']}:LeaderboardByEarnings", $row['earnings'], $row['earnings']);
                $pipe->zAdd("{$row['game']}:LeaderboardByScores", $row['score'], $row['score']);
            }
        });
    }


    private function countLeaderboard(string $type, int $game) : int
    {
        $key = "{$game}:CountLeaderboardBy" . ucfirst($type);
        $count = $this->cache->get($key, function() use ($game, $key, $type) {
            $count = $this->qb->select()
                ->where('game = ?', [$game])
                ->count();

            $this->cache->set($key, $count, (3 * 60));

            return $count;
        });

        return $count;
    }


    public function countLeaderboardByEarnings(int $game) : int
    {
        return $this->countLeaderboard('earnings', $game);
    }


    public function countLeaderboardByScores(int $game) : int
    {
        return $this->countLeaderboard('scores', $game);
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("{$entity->getGame()}", $entity->getUser());
                $pipe->hDel("FindByUser", $entity->getUser());
                $pipe->hDel("FindById", $entity->getId());
            }
        });
    }


    public function findByGameAndUsers(int $game, int ...$users) : Entities
    {
        $ids = $this->cache->hMGet("{$game}", $users, function(array $missing) use ($game) {
            $rows = $this->qb->select()
                ->fields('id', 'user')
                ->where("user IN {$this->qb->placeholders($missing)} AND game = ?", [$missing, $game])
                ->execute();

            return array_combine(array_column($rows, 'user'), array_column($rows, 'id'));
        });
        $rows = $this->findByIds(...$ids);

        $entities = [];
        $missing = array_diff($users, $rows->column('user'));

        foreach ($missing as $user) {
            $entities[] = $this->row(compact('game', 'user'))->getStorageValues();
        }

        foreach ($rows as $row) {
            $entities[] = $row->getStorageValues();
        }

        return $this->rows($entities);
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
            $rows = $this->qb->select()
                ->where("id IN {$this->qb->placeholders($missing)}", [$missing])
                ->execute();

            return array_combine(array_column($rows, 'id'), $rows);
        });

        $game = $rows[0]['game'] ?? 0;

        if ($game) {
            $key = "{$game}:CachebustLeaderboardTimer";
            $this->cache->get($key, function() use ($key, $game) {
                $this->cachebustLeaderboards($game);

                $this->cache->set($key, true, (3 * 60));

                return true;
            });
        }

        $ranks = $this->cache->pipeline(function($pipe) use ($rows) {
            foreach ($rows as $row) {
                $pipe->zRevRank("{$row['game']}:LeaderboardByScores", $row['score']);
            }
        });

        foreach ($rows as $index => $row) {
            $rows[$index]['rank'] = $ranks[$index] + 1;
        }

        return $this->rows($rows);
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


    private function leaderboard(string $type, int $game, int $limit, int $page) : Entities
    {
        $key = "{$game}:LeaderboardBy" . ucfirst($type) . ":{$page}";
        $ids = $this->cache->get($key, function() use ($game, $key, $limit, $page, $type) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where('game = ?', [$game])
                ->orderBy(str_replace('scores', 'score', $type) . ' DESC')
                ->page($limit, $page)
                ->execute();

            $this->cache->set($key, ($ids = array_column($rows, 'id')), (3 * 60));

            return $ids;
        });

        return $this->findByIds(...$ids);
    }


    public function leaderboardByEarnings(int $game, int $limit = 25, int $page = 1) : Entities
    {
        return $this->leaderboard('earnings', $game, $limit, $page);
    }


    public function leaderboardByScores(int $game, int $limit = 25, int $page = 1) : Entities
    {
        return $this->leaderboard('scores', $game, $limit, $page);
    }


    public function scheduleUpdateJob(array $args) : void
    {
        $this->scheduleLockedJob(UpdateCommand::class, 'execute', $args);
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
