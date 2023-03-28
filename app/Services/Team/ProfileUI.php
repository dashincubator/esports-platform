<?php

namespace App\Services\Team;

use App\Commands\User\Eligibility\Command as EligibilityCommand;
use App\Commands\User\Find\Command as FindCommand;
use App\DataSource\Event\Team\AbstractEntity as AbstractTeamEntity;
use App\DataSource\Ladder\Match\Mapper as LadderMatchMapper;
use App\DataSource\Ladder\Match\Report\Mapper as LadderMatchReportMapper;
use App\DataSource\Ladder\Team\Member\Mapper as LadderTeamMemberMapper;
use App\DataSource\User\Mapper as UserMapper;
use App\Services\Team\RosterUI as RosterUIService;

class ProfileUI
{

    private $command;

    private $mapper;

    private $roster;


    public function __construct(
        EligibilityCommand $eligibility,
        FindCommand $find,
        LadderMatchMapper $ladderMatch,
        LadderMatchReportMapper $ladderMatchReport,
        LadderTeamMemberMapper $ladderTeamMember,
        RosterUIService $roster,
        UserMapper $user
    )
    {
        $this->command = compact('eligibility', 'find');
        $this->mapper = compact(
            'ladderMatch', 'ladderMatchReport', 'ladderTeamMember',
            'user'
        );
        $this->service = compact('roster');
    }


    private function build(AbstractTeamEntity $team, array $eligibility, int $game, string $key) : array
    {
        $findBy  = $this->command['find']->buildOptions();
        $founder = $this->mapper['user']->findById($team->getCreatedBy());
        $members = $this->mapper["{$key}TeamMember"]->findByTeam($team->getId());

        $matches = $this->mapper["{$key}MatchReport"]->findMatchIdsByTeam($team->getId());
        rsort($matches);
        $matches = $this->mapper["{$key}Match"]->findByIds(...$matches);

        $messages = $this->command['eligibility']->execute(array_merge($eligibility, [
            'users' => $members->column('user')
        ]))->getErrorMessages();
        $roster = $this->service['roster']->build($members, $game, $messages);

        return compact('findBy', 'founder', 'matches', 'messages', 'roster');
    }


    public function ladder(AbstractTeamEntity $team, array $eligibility, int $game)
    {
        return $this->build($team, $eligibility, $game, 'ladder');
    }
}
