<?php

namespace App\DataSource\Ladder\Match;

use App\DataSource\Event\Match\AbstractMapper;
use App\Commands\Ladder\Match\Start\Command as StartCommand;
use App\Commands\Ladder\Match\Update\Command as UpdateCommand;

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

    protected $table = 'LadderMatch';


    public function cachebust(int ...$ladders) : void
    {
        $this->cache->pipeline(function($pipe) use ($ladders) {
            $pipe->delete("FindById");

            foreach ($ladders as $ladder) {
                $pipe->delete("FindByLadder:{$ladder}", "MatchFinder:{$ladder}");
            }
        });
    }


    public function countByGametype(int $gametype) : int
    {
        return $this->qb->select()
            ->where('gametype = ?', [$gametype])
            ->count();
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("FindById", $entity->getId());
                $pipe->sRem("FindByLadder:{$entity->getLadder()}", $entity->getId());
                $pipe->sRem("MatchFinder:{$entity->getLadder()}", $entity->getId());
            }
        });
    }


    public function findActiveIdsForUpdateJob() : array
    {
        $rows = $this->qb->select()
            ->fields('id')
            ->where('startedAt < ? AND status = ?', [(time() + (60 * 60)), self::STATUS_ACTIVE])
            ->execute();

        return array_column($rows, 'id');
    }


    public function findByEvent(int $event) : Entities
    {
        return $this->findByLadder($event);
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


    public function findByLadder(int $ladder) : Entities
    {
        $ids = $this->cache->sMembers("FindByLadder:{$ladder}", function() use ($ladder) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where('ladder = ?', [$ladder])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findMatchfinderMatches(int $ladder) : Entities
    {
        $ids = $this->cache->sMembers("MatchFinder:{$ladder}", function() use ($ladder) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where("ladder = ? AND status IN(?, ?)", [$ladder, [self::STATUS_MATCHFINDER, self::STATUS_UPCOMING]])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    // TODO: Add Config Variable For Match Start Time Interval
    public function findUpcomingIdsForStartJob() : array
    {
        $rows = $this->qb->select()
            ->fields('id')
            ->where('startedAt < ? AND status = ?', [(time() + (60 * 10)), self::STATUS_UPCOMING])
            ->execute();

        return array_column($rows, 'id');
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->upserted(...$entities);
    }


    public function scheduleStartJob(array $args, int $delay = 0)
    {
        $this->scheduleLockedJob(StartCommand::class, 'execute', $args, $delay);
    }


    public function scheduleUpdateJob(array $args)
    {
        $this->scheduleLockedJob(UpdateCommand::class, 'execute', $args);
    }


    protected function updated(Entity ...$entities) : void
    {
        $this->upserted(...$entities);
    }


    private function upserted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                if ($entity->inMatchFinder()) {
                    $pipe->delete("MatchFinder:{$entity->getLadder()}");
                }
                // Just In Case Match Was Just Accepted Delete From MatchFinder
                elseif ($entity->isActive()) {
                    $pipe->sRem("MatchFinder:{$entity->getLadder()}", $entity->getId());
                }

                $pipe->delete("FindByLadder:{$entity->getLadder()}");
                $pipe->hDel("FindById", $entity->getId());
            }
        });
    }
}
