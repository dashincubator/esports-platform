<?php

namespace App\DataSource\Ladder;

use App\DataSource\Event\AbstractMapper;

class Mapper extends AbstractMapper
{

    // TODO: Provider For REQUIRED_RANKED_MATCHES ( Arena Rating )
    private const REQUIRED_RANKED_MATCHES = 10;

    private const TEAM_LOCKED = 1;


    protected $entities = Entities::class;

    protected $entity = Entity::class;

    protected $record = Record::class;

    protected $table = 'Ladder';


    public function cachebust() : void
    {
        $this->cache->delete(
            'FindAllByOrganization',
            'Find',
            'FindByGame',
            'FindByGameAndSlug',
            'FindById'
        );
    }


    protected function deleted(Entity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            $pipe->delete('Find');

            foreach ($entities as $entity) {
                $pipe->hDel('FindAllByOrganization', $entity->getOrganization());
                $pipe->hDel('FindByGame', $entity->getGame());
                $pipe->hDel('FindByGameAndSlug', "{$entity->getGame()}:{$entity->getSlug()}");
                $pipe->hDel('FindById', $entity->getId());
            }
        });
    }


    public function findAll() : Entities
    {
        $ids = $this->cache->hGet("Find", "All", function() {
            $rows = $this->qb->select()
                ->fields('id')
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findAllByOrganization(int $organization) : Entities
    {
        $ids = $this->cache->hGet('FindAllByOrganization', $organization, function($organization) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where('organization = ?', [$organization])
                ->execute();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findByGame(int $game) : Entities
    {
        if (!$game) {
            return $this->rows();
        }

        $ids = $this->cache->hGet('FindByGame', $game, function($game) {
            $rows = $this->qb->select()
                ->fields('id')
                ->where('game = ?', [$game])
                ->first();

            return array_column($rows, 'id');
        });

        return $this->findByIds(...$ids);
    }


    public function findByGameOrganizationAndSlug(int $game, int $organization, string $slug) : Entity
    {
        $id = $this->cache->hGet("FindByGameOrganizationAndSlug", "{$game}:{$organization}:{$slug}", function() use ($game, $organization, $slug) {
            return $this->qb->select()
                ->fields('id')
                ->where('game = ? AND organization = ? AND slug = ? ', [$game, $organization, $slug])
                ->first()['id'] ?? 0;
        });

        return $this->findById($id);
    }


    public function findByIds(int ...$ids) : Entities
    {
        if (!$ids) {
            return $this->rows();
        }

        $rows = $this->cache->hMGet('FindById', $ids, function(array $missing) {
            $rows = $this->qb->select()
                ->where("id IN {$this->qb->placeholders($missing)}", [$missing])
                ->execute();

            return array_combine(array_column($rows, 'id'), $rows);
        });
        $stats = $this->findStatsByIds(...array_column($rows, 'id'));

        foreach ($stats as $stat) {
            $index = array_search($stat['id'], array_column($rows, 'id'));

            if ($index === false) {
                continue;
            }

            $rows[$index] = array_merge($rows[$index], $stat);
        }

        return $this->rows($rows);
    }


    protected function findStatsByIdsLookup(int ...$ids) : array
    {
        $totalLockedMembers = $this->qb->select()
            ->alias('totalLockedMembers')
            ->table('LadderTeamMember', 'member')
            ->fields('COUNT(member.id)')
            ->where('member.team = team.id AND team.locked = ' . self::TEAM_LOCKED);

        $totalWagered = $this->qb->select()
            ->alias('totalWagered')
            ->fields('SUM(match.wager)')
            ->table('LadderMatch', 'match')
            ->where('match.ladder = team.ladder');

        return $this->qb->select()
            ->table('LadderTeam', 'team')
            ->fields(
                $totalLockedMembers->getSQL(false),
                $totalWagered->getSQL(false),
                'COUNT(IF(team.wins + team.losses >= ' . self::REQUIRED_RANKED_MATCHES . ', 1, NULL)) as totalRankedTeams',
                'COUNT(team.id) as totalRegisteredTeams',
                'team.ladder as id'
            )
            ->where("team.ladder IN {$this->qb->placeholders($ids)}", [$ids])
            ->groupBy('team.ladder')
            ->execute();
    }


    protected function inserted(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function popTotalMatchesPlayedQueue(int $ladder)
    {
        return $this->cache->lPop("TotalMatchesPlayedQueue:{$ladder}");
    }


    public function pushTotalMatchesPlayedQueue(int $ladder) : void
    {
        $this->cache->lPush("TotalMatchesPlayedQueue:{$ladder}", 1);
    }


    public function popTotalWageredQueue(int $ladder)
    {
        return $this->cache->lPop("TotalWageredQueue:{$ladder}");
    }


    public function pushTotalWageredQueue(int $ladder, float $total) : void
    {
        $this->cache->lPush("TotalWageredQueue:{$ladder}", $total);
    }


    protected function updated(Entity ...$entities) : void
    {
        $this->deleted(...$entities);
    }
}
