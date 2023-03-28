<?php

namespace App\DataSource\User\Bank\Withdraw;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserBankWithdraw';


    public function cachebust() : void
    {
        $this->cache->delete("CountByUser", "FindById");
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
                $pipe->hDel("FindById", $entity->getId());
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


    public function findNextToProcess(int $organization) : Entity
    {
        $row = $this->qb->select()
            ->orderBy('createdAt DESC')
            ->where('organization = ? AND processedAt = ?', [$organization, 0])
            ->first();

        return $this->row($row);
    }


    public function findStatsForAdminPanel(int $organization) : array
    {
        return $this->qb->select()
            ->fields(
                'COUNT(id) as totalWithdraws',
                'COALESCE(SUM(amount), 0) as totalWithdrawAmount'
            )
            ->where('organization = ? AND processedAt = ?', [$organization, 0])
            ->first();
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function isUniqueTransaction(string $transaction) : bool
    {
        $count = $this->qb->select()
            ->where('processorTransactionId = ?', [$transaction])
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
