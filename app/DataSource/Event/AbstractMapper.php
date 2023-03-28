<?php

namespace App\DataSource\Event;

use App\DataSource\{AbstractEntity, AbstractMapper as AbstractParent};

abstract class AbstractMapper extends AbstractParent
{

    private const EXPIRE_STATS = 1;


    public function findById(int $id) : AbstractEntity
    {
        return $this->findByIds($id)->get('id', $id, $this->row());
    }


    protected function findStatsByIds(int ...$ids) : array
    {
        if (!$ids) {
            return [];
        }

        $keys = array_map(function($id) {
            return "Stats:{$id}";
        }, $ids);

        $rows = $this->cache->mGet($keys, function(array $missing) use ($keys) {
            $ids = array_map(function($id) {
                return array_reverse(explode(':', $id))[0];
            }, $missing);

            $rows = $this->findStatsByIdsLookup(...$ids);

            $this->cache->pipeline(function($pipe) use ($rows) {
                foreach ($rows as $row) {
                    $pipe->set("Stats:{$row['id']}", $row, (self::EXPIRE_STATS * 60));
                }
            });

            return $rows;
        });

        return array_filter(array_values($rows));
    }


    public function isUniqueSlug(int $game, string $slug) : bool
    {
        return $this->qb->select()
            ->where('game = ? AND slug = ?', [$game, $slug])
            ->count() === 0;
    }
}
