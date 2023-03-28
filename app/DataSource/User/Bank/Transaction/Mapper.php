<?php

namespace App\DataSource\User\Bank\Transaction;

use App\DataSource\AbstractMapper;
use App\Commands\Ladder\Match\Refund\Command as LadderMatchRefundCommand;
use App\Commands\Ladder\Match\Payout\Command as LadderMatchPayoutCommand;

class Mapper extends AbstractMapper
{

    // Subtracting From User Bank
    private const TYPE_CREDIT = 0;

    // Adding To User Bank
    private const TYPE_DEBIT = 1;


    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserBankTransaction';


    public function cachebust() : void
    {
        $this->cache->delete("CountByUser", "FindById", "FindByLadderMatch");
    }


    public function countByUser(int $user) : int
    {
        $count = $this->cache->hGet("CountByUser", $user, function() use ($user) {
            return $this->qb->select()
                ->fields('id')
                ->where('user = ?', [$user])
                ->count();
        });

        return $count;
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->delete("FindByUser:{$entity->getUser()}");
                $pipe->hDel("CountByUser", $entity->getUser());

                if ($entity->getId()) {
                    $pipe->hDel("FindById", $entity->getId());
                }

                if ($entity->getLadderMatch() > 0) {
                    $pipe->hDel("FindByLadderMatch", $entity->getLadderMatch());
                }
            }
        });
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


    public function findByLadderMatch(int $ladderMatch) : Entities
    {
        $ids = $this->cache->hGet("FindByLadderMatch", $ladderMatch, function($ladderMatch) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where('ladderMatch = ?', [$ladderMatch])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findByUser(int $user, int $limit = 25, int $page = 1) : Entities
    {
        $ids = $this->cache->hGet("FindByUser:{$user}", $page, function() use ($user, $limit, $page) {
            $rows = $this->qb->select()
                ->fields('id')
                ->orderBy('createdAt DESC')
                ->page($limit, $page)
                ->where('user = ?', [$user])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findPayoutsByLadderAndTeam(int $ladder, int $team) : Entities
    {
        $rows = $this->qb->select()
            ->where("ladder = ? AND ladderMatch = ? AND refundedAt = ? AND team = ? AND type = ?", [$ladder, 0, 0, $team, self::TYPE_DEBIT])
            ->execute();

        return $this->rows($rows);
    }


    public function findPayoutsByLadderMatchAndTeam(int $ladderMatch, int $team) : Entities
    {
        $rows = $this->qb->select()
            ->where("ladderMatch = ? AND refundedAt = ? AND team = ? AND type = ?", [$ladderMatch, 0, $team, self::TYPE_DEBIT])
            ->execute();

        return $this->rows($rows);
    }


    public function findRefundableByIds(int ...$ids) : Entities
    {
        $rows = $this->qb->select()
            ->where("id IN {$this->qb->placeholders($ids)} AND refundedAt = ? AND type = ?", [$ids, 0, self::TYPE_CREDIT])
            ->execute();

        return $this->rows($rows);
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function scheduleLadderMatchRefundJob(array $args)
    {
        $this->scheduleLockedJob(LadderMatchRefundCommand::class, 'execute', $args);
    }


    public function scheduleLadderMatchPayoutJob(array $args)
    {
        $this->scheduleLockedJob(LadderMatchPayoutCommand::class, 'execute', $args);
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
