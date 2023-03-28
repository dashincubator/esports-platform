<?php

namespace App\DataSource\Game;

use App\DataSource\AbstractMapper;

class Mapper extends AbstractMapper
{

    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'Game';


    public function cachebust() : void
    {
        $this->cache->delete(
            "FindAll",
            "FindById",
            "FindByPlatform",
            "FindBySlugs"
        );
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            $pipe->delete(
                "FindAll",
                "FindByPlatform",
                "FindBySlugs"
            );

            foreach ($entities as $entity) {
                $pipe->hDel("FindById", $entity->getId());
            }
        });
    }


    public function findAll() : Entities
    {
        $ids = $this->cache->hGetAll("FindAll", function() {
            $rows = $this->qb->select()
                ->fields('id')
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
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


    public function findByPlatform(int $platform) : Entities
    {
        $ids = $this->cache->hGet("FindByPlatform", $platform, function() use ($platform) {
            return $this->qb->select()
                ->fields('id')
                ->where('platform = ?', [$platform])
                ->execute();
        });

        return $this->findByIds(...$ids);
    }


    public function findBySlugs(string $game, string $platform) : Entity
    {
        $id = $this->cache->hGet("FindBySlugs", "{$platform}/{$game}", function() use ($game, $platform) {
            return $this->qb->select()
                ->fields('game.id')
                ->table('GamePlatform', 'platform')
                ->join('LEFT JOIN Game AS game ON game.platform = platform.id')
                ->where('game.slug = ? AND platform.slug = ?', [$game, $platform])
                ->first()['id'] ?? 0;
        });

        return $this->findById($id);
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function isUniqueSlug(int $platform, string $slug) : bool
    {
        $count = $this->qb->select()
            ->where('platform = ? AND slug = ?', [$platform, $slug])
            ->count();

        return $count === 0;
    }


    public function popTotalPrizesPaidQueue(int $game)
    {
        return $this->cache->lPop("TotalPrizesPaidQueue:{$game}");
    }


    public function pushTotalPrizesPaidQueue(int $game, float $total) : void
    {
        $this->cache->lPush("TotalPrizesPaidQueue:{$game}", $total);
    }


    protected function updated(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }
}
