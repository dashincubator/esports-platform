<?php

namespace App\DataSource\User\Bank\Deposit;

use App\Commands\User\Bank\Deposit\Process\Command as ProcessBankDepositCommand;
use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserBankDeposit';


    public function cachebust() : void
    {
        $this->cache->delete("CountByUser", "FindById", "FindByUser");
    }


    public function countByUser(int $user) : int
    {
        $count = $this->cache->hGet("CountByUser", $user, function() use ($user) {
            return $this->qb->select()
                ->fields('id')
                ->where('user = ? AND verifiedAt > ?', [$user, 0])
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
                ->where('user = ? AND verifiedAt > ?', [$user, 0])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findTransaction(int $organization, string $processor, string $transactionId, int $user) : Entity
    {
        $id = $this->qb->select()
            ->fields('id')
            ->where('organization = ? AND processor = ? AND processorTransactionId = ? AND user = ?', [$organization, $processor, $transactionId, $user])
            ->first()['id'] ?? 0;

        return $this->findById($id);
    }


    public function findNewDepositIds(int $limit = 50, int $page = 1) : array
    {
        $rows = $this->qb->select()
            ->fields('id')
            ->page($limit, $page)
            ->where('processedAt = ? AND verifiedAt > ?', [0, 0])
            ->execute();

        return array_column($rows, 'id');
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function isUniqueTransaction(string $transaction) : bool
    {
        $count = $this->qb->select()
            ->where('processorTransactionId = ? AND verifiedAt > ?', [$transaction, 0])
            ->count();

        return $count === 0;
    }


    public function scheduleProcessBankDepositJob(array $args) : void
    {
        $this->scheduleLockedJob(ProcessBankDepositCommand::class, 'execute', $args);
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
