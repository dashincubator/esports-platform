<?php

namespace App\Http\Actions\Web\Ladder\MatchFinder;

use App\User;
use App\Http\Actions\AbstractAction;
use App\DataSource\Ladder\Gametype\Mapper as GametypeMapper;
use App\DataSource\Ladder\Match\Mapper as MatchMapper;
use App\DataSource\Ladder\Match\Report\Mapper as MatchReportMapper;
use App\DataSource\Ladder\Team\Member\Mapper as MemberMapper;
use App\Commands\User\Eligibility\Command as EligibilityCommand;
use App\Services\Team\RosterUI as RosterUIService;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;

    private $mapper;

    private $service;

    private $user;


    public function __construct(
        EligibilityCommand $eligibility,
        GametypeMapper $gametype,
        MatchMapper $match,
        MatchReportMapper $report,
        MemberMapper $member,
        Responder $responder,
        RosterUIService $roster,
        User $user
    ) {
        $this->command = compact('eligibility');
        $this->mapper = compact('gametype', 'match', 'member', 'report');
        $this->responder = $responder;
        $this->service = compact('roster');
        $this->user = $user;
    }


    public function handle(Request $request) : Response
    {
        $ladder = $request->getAttributes()->get('ladder');
        $gametypes = $this->mapper['gametype']->findByIds(...$ladder->getGametypes());
        $matches = $this->mapper['match']->findMatchfinderMatches($ladder->getId());

        $disputed = [];
        $unreported = [];
        $roster = [];

        if (!$this->user->isGuest()) {
            $member = $this->mapper['member']->findByLadderAndUser($ladder->getId(), $this->user->getId());

            if (!$member->isEmpty()) {
                $members = $this->mapper['member']->findByTeam($member->getTeam());
                $messages = $this->command['eligibility']->execute([
                    'game' => $ladder->getGame(),
                    'membership' => $ladder->isMembershipRequired(),
                    'users' => $members->column('user')
                ])->getErrorMessages();

                $disputed = $this->mapper['report']->findDisputedMatchIdsByTeam($member->getTeam());
                $roster = $this->service['roster']->build($members, $ladder->getGame(), $messages);
            }

            $unreported = $this->mapper['report']->findUserTeamsWithUnreportedMatches(
                $this->user->getId(),
                ...$this->mapper['member']->findTeamsByUser($this->user->getId())->column('team')
            );
        }

        $counters = [
            'disputed' => count($disputed),
            'unreported' => count($unreported)
        ];

        return $this->responder->handle(compact('counters', 'gametypes', 'ladder', 'matches', 'unreported', 'roster'));
    }
}
