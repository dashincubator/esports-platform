<?php

namespace App\Commands\Ladder\Team\Update\GameStats;

use App\Commands\AbstractCommand;
use App\DataSource\Ladder\{Entity as LadderEntity, Mapper as LadderMapper};
use App\DataSource\Ladder\Team\{Entity as TeamEntity, Mapper as TeamMapper};
use App\DataSource\Ladder\Team\Member\{Entities as TeamMemberEntities, Entity as TeamMemberEntity, Mapper as TeamMemberMapper};
use App\DataSource\Game\Mapper as GameMapper;
use App\DataSource\Game\Api\Match\Mapper as GameApiMatchMapper;
use App\Services\Api\Managers;

class Command extends AbstractCommand
{

    private $limit = 50;

    private $managers;

    private $mapper;

    private $page = 1;


    public function __construct(
        GameMapper $game,
        GameApiMatchMapper $gameMatch,
        LadderMapper $ladder,
        Managers $managers,
        TeamMapper $team,
        TeamMemberMapper $member
    ) {
        $this->managers = $managers;
        $this->mapper = compact('game', 'gameMatch', 'ladder', 'member', 'team');
    }


    public function run(int $ladder) : void
    {
        $close = false;
        $ladder = $this->mapper['ladder']->findById($ladder);

        $api = $this->managers->getApiManagingAccount(
            $this->mapper['game']->findById($ladder->getGame())->getAccount()
        );

        while (!($teams = $this->mapper['team']->findExpiredScoresByLadder($ladder->getId(), $this->limit, $this->page))->isEmpty()) {
            $update = [
                'member' => [],
                'team' => []
            ];

            foreach ($teams as $team) {
                $members = $this->mapper['member']->findByTeam($team->getId());

                if (!$this->update($ladder, $members, $team, $api, $ladder->getFormat())) {
                    continue;
                }

                // If Top Score Was Met Event Is Over
                if ($ladder->getFirstToScore() && $team->getScore() >= $ladder->getFirstToScore()) {
                    $close = true;
                }

                $update['team'][] = $team;
                $update['member'] = array_merge($update['member'], iterator_to_array($members));
            }

            $this->mapper['team']->update(...$update['team']);
            $this->mapper['member']->update(...$update['member']);
            $this->page++;
        }

        if ($close) {
            $ladder->close();
            $this->mapper['ladder']->update($ladder);
        }
    }


    private function update(LadderEntity $ladder, TeamMemberEntities $members, TeamEntity $team, string $api, string $format) : bool
    {
        $score = 0;
        $matches = $this->mapper['gameMatch']->findByApiAndUsernames(
            $api,
            $ladder->getEndsAt(),
            ($ladder->getStartsAt() > $team->getLockedAt() ? $ladder->getStartsAt() : $team->getLockedAt()),
            ...$members->column('account')
        );
        $update = false;

        foreach ($members as $member) {
            if (!$member->updateGameStatsScore(
                $this->managers->calculateScore($matches->filter(function($match) use ($member) {
                    return $match->getUsername() === $member->getAccount();
                }), $format, count($members))
            )) {
                continue;
            }

            $score += $member->getScore();
            $update = true;
        }

        return $team->updateGameStatsScore($score) || $update;
    }
}
