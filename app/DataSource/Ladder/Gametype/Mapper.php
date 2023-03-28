<?php

namespace App\DataSource\Ladder\Gametype;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'LadderGametype';


    public function cachebust() : void
    {
        $this->cache->delete("FindById");
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("FindById", $entity->getId());
                $pipe->hDel("FindByGameIds", $entity->getGame());
            }
        });
    }


    public function findByGameIds(int ...$games) : Entities
    {
        if (!$games) {
            return $this->rows();
        }

        $ids = $this->cache->hMGet("FindByGameIds", $games, function(array $missing) {
            $map = [];
            $rows = $this->qb->select()
                ->fields('game', 'id')
                ->where("game IN {$this->qb->placeholders($missing)}", [$missing])
                ->execute();

            foreach ($rows as $row) {
                $map[$row['game']][] = $row['id'];
            }

            return $map;
        });

        return $this->findByIds(...array_merge(...array_values($ids)));
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


    protected function inserted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->delete("FindByGameIds");
                $pipe->hDel("FindById", $entity->getId());
            }
        });
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
