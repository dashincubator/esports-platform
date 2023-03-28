<?php

namespace App\Http\Actions\Web\Ladder\Team\Profile;

use App\Http\Actions\AbstractAction;
use App\DataSource\Game\Api\Match\Mapper as GameApiMatchMapper;
use App\DataSource\Ladder\Gametype\Mapper as GametypeMapper;
use App\Services\Api\Managers;
use App\Services\Team\ProfileUI as ProfileUIService;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;

    private $mapper;

    private $service;


    public function __construct(GameApiMatchMapper $gameMatch, GametypeMapper $gametype, Managers $managers, ProfileUIService $profile, Responder $responder)
    {
        $this->managers = $managers;
        $this->mapper = compact('gameMatch', 'gametype');
        $this->responder = $responder;
        $this->service = compact('profile');
    }


    public function handle(Request $request) : Response
    {
        $ladder = $request->getAttributes()->get('ladder');
        $team = $request->getAttributes()->get('team');

        $gametypes = $this->mapper['gametype']->findByIds(...$ladder->getGametypes());
        $data = array_merge(
            $this->service['profile']->ladder($team, [
                'accountModifiedAt' => ($team->getLockedAt() ?? null),
                'amount' => ($ladder->isTeamLockRequired() && !$team->isLocked() ? $ladder->getEntryFee() : 0),
                'game' => $ladder->getGame(),
                'gameStat' => ($ladder->getFormat() !== ''),
                'membership' => $ladder->isMembershipRequired()
            ], $ladder->getGame()),
            compact('gametypes', 'team', 'ladder') 
        );

        if (!$ladder->isMatchFinderRequired() && $team->isLocked()) {
            $matches = [];
            $sort = [];
            $warzone = $this->mapper['gameMatch']->findByApiAndUsernames(
                $this->managers->getApiManagingAccount($request->getAttributes()->get('game')->getAccount()),
                $ladder->getEndsAt(),
                ($ladder->getStartsAt() > $team->getLockedAt() ? $ladder->getStartsAt() : $team->getLockedAt()),
                ...array_column($data['roster'], 'account')
            )->filterWarzone();

            $names = [];

            foreach ($data['roster'] as $member) {
                $names[$member['account']] = $member['user']->getUsername();
            }

            foreach ($warzone as $w) {
                $match = $w->toArray();
                $match['username'] = $names[$match['username']];

                $sort[$w->getStartedAt()][] = $match;
            }

            krsort($sort);

            foreach ($sort as $time => $sorted) {
                foreach ($sorted as $match) {
                    $matches[$match['data']['matchID']][] = $match;
                }
            }

            $data['matches'] = $matches;
        }

        return $this->responder->handle($data);
    }
}
