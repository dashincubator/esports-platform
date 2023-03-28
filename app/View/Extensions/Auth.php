<?php

namespace App\View\Extensions;

use App\{User, Organization};
use App\DataSource\Ladder\Team\Member\Mapper as LadderTeamMemberMapper;
use App\DataSource\Ladder\Team\Mapper as LadderTeamMapper;
use App\DataSource\User\Mapper as UserMapper;
use Exception;

class Auth
{

    private const EVENT_LIST = ['ladder'];


    private $ladder;

    private $mapper;

    private $user;


    public function __construct(
        LadderTeamMapper $ladderTeam,
        LadderTeamMemberMapper $ladderTeamMember,
        User $user,
        UserMapper $userMapper
    ) {
        $this->mapper = array_merge(compact('ladderTeam', 'ladderTeamMember'), [
            'user' => $userMapper
        ]);
        $this->user = $user;

        $this->boot();
    }


    public function __call(string $method, array $args)
    {
        return $this->user->{$method}(...$args);
    }


    private function boot() : void
    {
        if ($this->user->isGuest()) {
            foreach (self::EVENT_LIST as $key) {
                $this->{$key} = [
                    'invites' => [],
                    'teamids' => [],
                    'members' => [],
                    'teams' => []
                ];
            }
            return;
        }

        foreach (self::EVENT_LIST as $key) {
            $members = $this->mapper["{$key}TeamMember"]->findTeamsByUser($this->user->getId());
            $teamids = $members->column('team');

            $this->{$key} = [
                'invites' => $this->mapper["{$key}Team"]->findByIds(
                    ...$this->mapper["{$key}TeamMember"]->findInvitesByUser($this->user->getId())->column('team')
                ),
                'teamids' => $teamids,
                'members' => $members,
                'teams' => $this->mapper["{$key}Team"]->findByIds(...$teamids)
            ];
        }
    }


    private function get(string $key) : array
    {
        $getting = [];

        foreach (self::EVENT_LIST as $event) {
            $getting[$event] = $this->{$event}[$key];
        }

        return $getting;
    }


    public function getSiteCounters() : array
    {
        if (!$this->user->can('owner')) {
            return [];
        }

        return $this->mapper['user']->findCounters();
    }


    private function getMembers() : array
    {
        return $this->get('members');
    }


    private function getMemberByTeam(int $team)
    {
        foreach ($this->getMembers() as $key => $members) {
            foreach ($members as $member) {
                if ($member->getTeam() !== $team) {
                    continue;
                }

                return $member;
            }
        }

        throw new Exception("'" . $this->user->getId() . "' Is Not On Team: '{$team}'");
    }


    public function getTeams() : array
    {
        return $this->get('teams');
    }


    public function getTeamIds() : array
    {
        return $this->get('teamids');
    }


    public function getTeamById(int $id)
    {
        foreach ($this->getTeams() as $event => $teams) {
            foreach ($teams as $team) {
                if ($team->getId() !== $id) {
                    continue;
                }

                return $team;
            }
        }

        throw new Exception("'" . $this->user->getId() . "' Is Not On Team: '{$team}'");
    }


    public function getTeamByLadder(int $ladder)
    {
        foreach ($this->getTeams()['ladder'] as $team) {
            if ($team->getLadder() !== $ladder) {
                continue;
            }

            return $team;
        }

        throw new Exception("'" . $this->user->getId() . "' Is Not On A Team In Ladder: '{$ladder}'");
    }


    public function getTeamInvites() : array
    {
        return $this->get('invites');
    }


    public function managesMatches(int $team) : bool
    {
        if (!$this->onTeam($team)) {
            return false;
        }

        return $this->getMemberByTeam($team)->managesMatches();
    }


    public function managesTeam(int $team) : bool
    {
        if (!$this->onTeam($team)) {
            return false;
        }

        return $this->getMemberByTeam($team)->managesTeam();
    }


    public function onLadderTeam(int $ladder) : bool
    {
        if ($this->user->isGuest()) {
            return false;
        }

        return in_array($ladder, $this->getTeams()['ladder']->column('ladder'));
    }


    public function onTeam(int $team) : bool
    {
        if ($this->user->isGuest()) {
            return false;
        }

        foreach ($this->getTeamIds() as $event => $teamids) {
            if (!in_array($team, $teamids)) {
                continue;
            }

            return true;
        }

        return false;
    }
}
