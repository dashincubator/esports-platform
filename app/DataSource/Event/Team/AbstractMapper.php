<?php

namespace App\DataSource\Event\Team;

use App\DataSource\{AbstractEntities, AbstractEntity, AbstractMapper as AbstractParent};

abstract class AbstractMapper extends AbstractParent
{

    public function cachebust() : void
    {
        $this->cache->delete(
            "FindById",
            "FindByEventAndSlug"
        );
    }


    protected function deleted(AbstractEntity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                $pipe->hDel("FindById", ($entity->getId() ?? 0));
                $pipe->hDel("FindByEventAndSlug", "{$entity->{'get' . ucfirst($this->foreignKey)}()}:{$entity->getSlug()}");
            }
        });
    }


    public function findByEvent(int $event) : AbstractEntities
    {
        throw new Exception("FindByEvent Has Not Been Defined In " . get_called_class());
    }


    protected function findByEventAndSlug(int $event, string $slug) : AbstractEntity
    {
        $id = $this->cache->hGet("FindByEventAndSlug", "{$event}:{$slug}", function() use ($event, $slug) {
            return $this->qb->select()
                ->fields('id')
                ->where("{$this->foreignKey} = ? AND slug = ?", [$event, $slug])
                ->first()['id'] ?? 0;
        });

        return $this->findById($id);
    }


    public function findById(int $id) : AbstractEntity
    {
        return $this->findByIds($id)->get('id', $id, $this->row());
    }


    public function findByIds(int ...$ids) : AbstractEntities
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


    public function isUniqueNameAndSlug(int $event, string $name, string $slug) : bool
    {
        $count = $this->qb->select()
            ->where("{$this->foreignKey} = ? AND (name = ? OR slug = ?)", [$event, $name, $slug])
            ->count();

        return $count === 0;
    }


    protected function inserted(AbstractEntity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    protected function updated(AbstractEntity ...$entities) : void
    {
        $this->deleted(...$entities);
    }
}
