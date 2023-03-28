<?php

namespace App\DataSource\Ladder\Match\Report;

use App\DataSource\Event\Match\Report\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected const STATUS_COMPLETE = 3;

    protected const STATUS_DISPUTE = 4;

    protected const STATUS_MATCHFINDER = 0;

    protected const STATUS_ACTIVE = 2;

    protected const STATUS_UPCOMING = 1;


    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'LadderMatchReport';


    public function cachebust() : void
    {
        $this->cache->delete(
            "FindActiveMatchIdsByTeam",
            "FindDisputedMatchIdsByTeam",
            "FindById",
            "FindByMatch",
            "FindByTeam",
            "UnreportedMatches"
        );
    }


    public function countByMatch(int $match) : int
    {
        return $this->qb->select()
            ->where('match = ?', [$match])
            ->count();
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("FindActiveMatchIdsByTeam", $entity->getTeam());
                $pipe->hDel("FindDisputedMatchIdsByTeam", $entity->getTeam());
                $pipe->hDel("FindById", $entity->getId());
                $pipe->hDel("FindByMatch", $entity->getMatch());
                $pipe->sRem("FindByTeam:{$entity->getTeam()}", $entity->getId());

                foreach ($entity->getRoster() as $user) {
                    $pipe->hDel("UnreportedMatches", $user);
                }
            }
        });
    }


    public function findActiveMatchIdsByTeam(int $team) : array
    {
        return $this->cache->hGet("FindActiveMatchIdsByTeam", $team, function($team) {
            $rows = $this->qb->select()
                ->fields('match.id')
                ->table('LadderMatchReport', 'report')
                ->join('LEFT JOIN LadderMatch as match on match.id = report.match')
                ->where('match.status != ? AND report.team = ?', [self::STATUS_COMPLETE, $team])
                ->execute();

            return array_column($rows, 'id');
        });
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


    public function findByMatch(int $match) : Entities
    {
        return $this->findByMatches($match);
    }


    public function findByMatches(int ...$matches) : Entities
    {
        if (!$matches) {
            return $this->rows();
        }

        $ids = $this->cache->hMGet("FindByMatch", $matches, function(array $missing) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where("match IN {$this->qb->placeholders($missing)}", [$missing])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findDisputedMatchIdsByTeam(int $team) : array
    {
        return $this->cache->hGet("FindActiveMatchIdsByTeam", $team, function($team) {
            $rows = $this->qb->select()
                ->fields('match.id')
                ->table('LadderMatchReport', 'report')
                ->join('LEFT JOIN LadderMatch as match on match.id = report.match')
                ->where('match.status = ? AND report.team = ?', [self::STATUS_DISPUTE, $team])
                ->execute();

            return array_column($rows, 'id');
        });
    }


    public function findMatchIdsByTeam(int $team) : array
    {
        $ids = $this->cache->sMembers("FindByTeam:{$team}", function() use ($team) {
            $rows = $this->qb->select()
                ->fields('match.id')
                ->table('LadderMatchReport', 'report')
                ->join('LEFT JOIN LadderMatch as match on match.id = report.match')
                ->orderBy('report.createdAt DESC')
                ->where('match.status >= ? AND report.team = ?', [self::STATUS_ACTIVE, $team])
                ->execute();

            return array_column($rows, 'id');
        });

        return $ids;
    }


    public function findUserTeamsWithUnreportedMatches(int $user, int ...$teams) : array
    {
        if (!$teams) {
            return [];
        }

        $unreported = $this->cache->hGet("UnreportedMatches", $user, function() use ($user, $teams) {
            $rows = $this->rows(
                $this->qb->select()
                    ->fields('team', 'roster')
                    ->where("reportedAt = ? AND team IN {$this->qb->placeholders($teams)}", [0, $teams])
                    ->execute()
            );
            $teams = [];

            foreach ($rows as $row) {
                if (!in_array($user, $row->getRoster())) {
                    continue;
                }

                $teams[] = $row->getTeam();
            }

            return array_unique($teams);
        });

        return $unreported;
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
                $pipe->hDel("FindActiveMatchIdsByTeam", $entity->getTeam());
                $pipe->hDel("FindDisputedMatchIdsByTeam", $entity->getTeam());
                $pipe->delete("FindByTeam:{$entity->getTeam()}");

                foreach ($entity->getRoster() as $user) {
                    $pipe->hDel("UnreportedMatches", $user);
                }
            }
        });
    }
}
