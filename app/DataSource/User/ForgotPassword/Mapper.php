<?php

namespace App\DataSource\User\ForgotPassword;

use App\Commands\User\ForgotPassword\DeleteExpired\Command as DeleteExpiredCommand;
use App\Commands\User\ForgotPassword\Send\Command as SendEmailCommand;
use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'UserForgotPassword';


    public function cachebust() : void
    {
        $this->cache->delete("FindById", "FindByUser");
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("FindById", $entity->getId());
                $pipe->hDel("FindByUser", $entity->getUser());
            }
        });
    }


    public function findById(int $id) : Entity
    {
        if (!$id) {
            return $this->row();
        }

        $row = $this->cache->hGet("FindById", $id, function() use ($id) {
            return $this->qb->select()
                ->where('id = ?', [$id])
                ->first();
        });

        return $this->row($row);
    }


    public function findByUser(int $user) : Entity
    {
        $id = $this->cache->hGet("FindByUser", $user, function() use ($user) {
            return $this->qb->select()
                ->fields('id')
                ->where('emailedAt = ? AND user = ?', [0, $user])
                ->first()['id'] ?? 0;
        });

        return $this->findById($id);
    }


    public function findByUserOrCreate(int $user) : Entity
    {
        $row = $this->findByUser($user);

        if ($row->isEmpty()) {
            $this->insert($row = $this->row(compact('user')));

            if ($row->getId()) {
                $this->scheduleSendEmailJob([
                    'id' => $row->getId()
                ]);
            }
        }

        return $row;
    }


    public function findExpired(int $minutes) : Entities
    {
        $expired = time() - ($minutes * 60);
        $rows = $this->qb->select()
            ->where("createdAt < ?", [$expired])
            ->execute();

        return $this->rows($rows);
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function scheduleSendEmailJob(array $args) : void
    {
        $this->scheduleLockedJob(SendEmailCommand::class, 'execute', $args);
    }


    public function scheduleDeleteExpiredJob() : void
    {
        $this->scheduleLockedJob(DeleteExpiredCommand::class, 'execute');
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
