<?php

namespace App\DataSource\Ladder\Team;

use App\DataSource\{AbstractEntities, AbstractEntity};
use App\DataSource\Event\Team\AbstractMapper;
use App\Commands\Ladder\Team\Update\Glicko2\Command as UpdateGlicko2Command;
use App\Commands\Ladder\Team\Update\GameStats\Command as UpdateGameStatsCommand;
use App\Commands\Ladder\Team\Update\Leaderboards\Command as UpdateLeaderboardsCommand;

class Mapper extends AbstractMapper
{

    private const UPDATE_LEADERBOARD = 2;

    private const UPDATE_SCORES = 5;

    private const LIMIT = 25;


    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'LadderTeam';


    // Used By TeamMapper
    protected $foreignKey = 'ladder';


    public function cachebust(int ...$ladders) : void
    {
        parent::cachebust();

        $this->cache->delete("FindByLadder");
        $this->refreshLeaderboards(...$ladders);
    }


    public function countLeaderboardByScores(int $ladder) : int
    {
        $count = $this->cache->hGet('CountLeaderboardByScores', $ladder, function() use ($ladder) {
            return $this->qb->select()
                ->where('ladder = ?', [$ladder])
                ->count();
        });

        return $count;
    }


    public function deleted(AbstractEntity ...$entities) : void
    {
        parent::deleted(...$entities);

        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("FindByLadder", $entity->getLadder());
            }
        });
    }


    public function findByEvent(int $event) : AbstractEntities
    {
        return $this->findByLadder($event);
    }


    public function findByIds(int ...$ids) : AbstractEntities
    {
        return $this->rows($this->findByIdsQuery(...$ids));
    }


    private function findByIdsQuery(int ...$ids) : array
    {
        if (!$ids) {
            return [];
        }

        $rows = $this->cache->hMGet("FindById", $ids, function(array $missing) {
            $rows = $this->qb->select()
                ->where("id IN {$this->qb->placeholders($missing)}", [$missing])
                ->execute();

            return array_combine(array_column($rows, 'id'), $rows);
        });

        $ranks = $this->cache->pipeline(function($pipe) use ($rows) {
            foreach ($rows as $row) {
                $pipe->zRevRank("RankByScore:{$row['ladder']}", $row['score']);
            }
        });

        foreach ($rows as $index => $row) {
            if ($row['score'] === 0) {
                continue;
            }

            $rows[$index]['rank'] = $ranks[$index] + 1;
        }

        return $rows;
    }


    public function findExpiredScoresByLadder(int $ladder, int $limit, int $page) : AbstractEntities
    {
        $rows = $this->qb->select()
            ->fields('id')
            ->where('ladder = ? AND locked = ? AND scoreModifiedAt < ?', [$ladder, 1, (time() - (self::UPDATE_SCORES * 60))])
            ->page($limit, $page)
            ->execute();

        return $this->findByIds(...array_column($rows, 'id'));
    }


    public function findByLadderAndSlug(int $ladder, string $slug) : AbstractEntity
    {
        return $this->findByEventAndSlug($ladder, $slug);
    }


    public function findByLadder(int $ladder) : AbstractEntities
    {
        $ids = $this->cache->hGet("FindByLadder", $ladder, function($ladder) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where('ladder = ?', [$ladder])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function leaderboardByScores(int $ladder, int $limit = 25, int $page = 1) : AbstractEntities
    {
        $ids = $this->cache->hGet("LeaderboardByScores:{$ladder}", $page, function() use ($ladder, $limit, $page) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where('ladder = ?', [$ladder])
                ->orderBy('score DESC')
                ->page($limit, $page)
                ->execute();

            return array_column($rows, 'id');
        });

        $rows = $this->findByIdsQuery(...$ids);

        if (count($rows)) {
            array_multisort(array_column($rows, 'score'), SORT_DESC, $rows);
        }

        return $this->rows($rows);
    }


    public function refreshLeaderboards(int ...$ladders) : void
    {
        $pages = [];
        $scores = [];

        foreach ($ladders as $ladder) {
            $rows = $this->qb->select()
                ->fields('score')
                ->flags('distinct')
                ->where('ladder = ?', [$ladder])
                ->execute();

            $pages[$ladder] = array_keys($this->cache->hGetAll("LeaderboardByScores:{$ladder}", function() {
                return [];
            }));
            $scores[$ladder] = array_unique(array_column($rows, 'score'));
        }
  
        foreach ($pages as $ladder => $values) {
            $this->cache->delete("LeaderboardByScores:{$ladder}");

            foreach ($values as $page) {
                $this->leaderboardByScores($ladder, self::LIMIT, $page);
            }
        }

        $this->cache->pipeline(function($pipe) use ($scores) {
            $pipe->delete('CountLeaderboardByScores');

            foreach ($scores as $ladder => $values) {
                $pipe->delete("RankByScore:{$ladder}");

                foreach ($values as $score) {
                    $pipe->zAdd("RankByScore:{$ladder}", $score, $score);
                }
            }
        });
    }


    public function scheduleUpdateLeaderboardsJob() : void
    {
        $this->scheduleLockedJob(UpdateLeaderboardsCommand::class, 'execute');
    }


    public function scheduleUpdateGameStatsJob(array $args) : void
    {
        $this->scheduleLockedJob(UpdateGameStatsCommand::class, 'execute', $args);
    }


    public function scheduleUpdateGlicko2Job(array $args) : void
    {
        $this->scheduleLockedJob(UpdateGlicko2Command::class, 'execute', $args);
    }


    protected function updated(AbstractEntity ...$entities) : void
    {
        $this->deleted(...$entities);

        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $score = $entity->getScore();

                if ($score === 0) {
                    continue;
                }

                $pipe->zAdd("RankByScore:{$entity->getLadder()}", $score, $score);
            }
        });
    }
}
