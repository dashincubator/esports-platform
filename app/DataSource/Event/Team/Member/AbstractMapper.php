<?php

namespace App\DataSource\Event\Team\Member;

use App\DataSource\{AbstractEntities, AbstractEntity, AbstractMapper as AbstractParent};

abstract class AbstractMapper extends AbstractParent
{

    private const STATUS_ACTIVE = 1;

    private const STATUS_INVITE = 0;


    public function cachebust() : void
    {
        $this->cache->delete(
            "FindAllByUser",
            "FindById",
            "FindByTeams",
            "FindByUsers",
            "FindByEventAndUser"
        );
    }


    public function countMembersOfTeam(int $team) : int
    {
        return count($this->findByTeams($team));
    }


    protected function deleted(AbstractEntity ...$entities) : void
    {
        $this->cache->pipeline(function($pipe) use ($entities) {
            foreach ($entities as $entity) {
                if ($entity->getId()) {
                    $pipe->hDel("FindById", $entity->getId());
                }

                $pipe->hDel("FindAllByUser", $entity->getUser());
                $pipe->hDel("FindByTeams", $entity->getTeam());
                $pipe->hDel("FindByUsers", $entity->getUser());

                if (!$entity->isInvite()) {
                    $pipe->hDel("FindByEventAndUser", $entity->getUser());
                }
            }
        });
    }


    protected function findByEventAndUser(int $event, int $user) : AbstractEntity
    {
        $id = $this->cache->hGet("FindByEventAndUser", $user, function($user) use ($event) {
            $rows = $this->qb->select()
                ->table($this->table, 'member')
                ->fields('member.id', "team.{$this->foreignKey}")
                ->join("LEFT JOIN {$this->foreignTable} AS team ON member.team = team.id")
                ->where("member.status = ? AND member.user = ? AND team.{$this->foreignKey}", [self::STATUS_ACTIVE, $user, $event])
                ->execute();

            return array_combine(array_column($rows, $this->foreignKey), array_column($rows, 'id'));
        })[$event] ?? 0;

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


    public function findByTeamAndUser(int $team, int $user) : AbstractEntity
    {
        return $this->findByTeams($team)->get('user', $user, $this->row());
    }


    public function findByTeam(int $team) : AbstractEntities
    {
        return $this->findByTeams($team);
    }


    public function findByTeams(int ...$teams) : AbstractEntities
    {
        $ids = $this->cache->hMGet("FindByTeams", $teams, function(array $missing) {
            $rosters = [];
            $rows = $this->qb->select()
                ->fields('id', 'team')
                ->where("status = ? AND team IN {$this->qb->placeholders($missing)}", [self::STATUS_ACTIVE, $missing])
                ->execute();

            foreach ($rows as $row) {
                $rosters[$row['team']][] = $row['id'];
            }

            return $rosters;
        });

        if ($ids) {
            return $this->findByIds(...array_merge(...array_values($ids)));
        }

        return $this->findByIds();
    }


    public function findByUsers(int ...$users) : AbstractEntities
    {
        $ids = $this->cache->hMGet("FindByUsers", $users, function(array $missing) {
            $rows = $this->qb->select()
                ->fields('id', 'user')
                ->where("status = ? AND user IN {$this->qb->placeholders($missing)}", [self::STATUS_ACTIVE, $missing])
                ->execute();

            return array_combine(array_column($rows, 'user'), array_column($rows, 'id'));
        });

        return $this->findByIds(...$ids);
    }


    private function findAllByUser(int $user) : array
    {
        return $this->cache->hGet("FindAllByUser", $user, function($user) {
            $invites = [];
            $teams = [];

            $rows = $this->qb->select()
                ->fields('id', 'status')
                ->where("user = ?", [$user])
                ->execute();

            foreach ($rows as $row) {
                if ($row['status'] === self::STATUS_ACTIVE) {
                    $teams[] = $row['id'];
                }
                elseif ($row['status'] === self::STATUS_INVITE) {
                    $invites[] = $row['id'];
                }
            }

            return compact('invites', 'teams');
        });
    }


    public function findExpiredInvites(int $minutes) : AbstractEntities
    {
        $expired = time() - ($minutes * 60);
        $rows = $this->qb->select()
            ->where("createdAt < ? AND status = ?", [$expired, self::STATUS_INVITE])
            ->execute();

        return $this->rows($rows);
    }


    public function findInviteByTeamAndUser(int $team, int $user) : AbstractEntity
    {
        return $this->findInvitesByUser($user)->get('team', $team, $this->row());
    }


    public function findInvitesByUser(int $user) : AbstractEntities
    {
        return $this->findByIds(...$this->findAllByUser($user)['invites']);
    }


    public function findTeamsByUser(int $user) : AbstractEntities
    {
        return $this->findByIds(...$this->findAllByUser($user)['teams']);
    }


    protected function inserted(AbstractEntity ...$entities) : void
    {
        $this->deleted(...$entities);
    }


    public function onAnyTeam(int $user) : bool
    {
        return count($this->findAllByUser($user)['teams']) > 0;
    }


    public function onExistingTeam(int $event, int $user) : bool
    {
        return $this->findByEventAndUser($event, $user)->isEmpty() === false;
    }


    public function onTeam(int $team, int $user) : bool
    {
        return in_array($team, $this->findAllByUser($user)->column('team'));
    }


    protected function updated(AbstractEntity ...$entities) : void
    {
        $this->deleted(...$entities);
    }
}
