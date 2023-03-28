<?php

namespace App\Services\Match;

use App\Commands\User\Eligibility\Command as EligibilityCommand;
use App\DataSource\Event\Match\AbstractEntity as AbstractMatchEntity;
use App\DataSource\Ladder\Match\Report\Mapper as LadderMatchReportMapper;
use App\DataSource\Ladder\Team\Mapper as LadderTeamMapper;
use App\DataSource\Ladder\Team\Member\Mapper as LadderTeamMemberMapper;
use App\Services\Match\SortHost as SortHostService;
use App\Services\Team\RosterUI as RosterUIService;

class MatchUI
{

    private $command;

    private $mapper;

    private $roster;


    public function __construct(
        EligibilityCommand $eligibility,
        LadderMatchReportMapper $ladderMatchReport,
        LadderTeamMapper $ladderTeam,
        LadderTeamMemberMapper $ladderTeamMember,
        RosterUIService $roster,
        SortHostService $host
    )
    {
        $this->command = compact('eligibility');
        $this->mapper = compact(
            'ladderMatchReport', 'ladderTeam', 'ladderTeamMember'
        );
        $this->service = compact('host', 'roster');
    }


    private function execute(AbstractMatchEntity $match, array $eligibility, int $game, string $key) : array
    {
        $reports = $this->mapper["{$key}MatchReport"]->findByMatch($match->getId());
        $rosters = [];

        $members = $this->mapper["{$key}TeamMember"]->findByUsers(...array_merge(...$reports->column('roster')));
        $messages = $this->command['eligibility']->execute(array_merge($eligibility, [
            'users' => $members->column('user')
        ]))->getErrorMessages();
        $teams = $this->mapper["{$key}Team"]->findByIds(...$reports->column('team'));

        foreach ($teams as $team) {
            $rosters[$team->getId()] = $this->service['roster']->build($members->filter(function($entity) use ($team) {
                return $entity->getTeam() === $team->getId();
            }), $game, $messages);
        }

        return compact('reports', 'rosters', 'teams');
    }


    public function ladder(AbstractMatchEntity $match, array $eligibility, int $game)
    {
        return $this->execute($match, $eligibility, $game, 'ladder');
    }
}
