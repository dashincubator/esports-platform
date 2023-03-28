<?php

namespace App\Http\Actions\Web\Admincp\Ladder;

use App\User;
use App\DataSource\Ladder\Gametype\Mapper as GametypeMapper;
use App\DataSource\Ladder\Team\Mapper as TeamMapper;
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    public function __construct(GametypeMapper $gametype, Responder $responder, TeamMapper $team, User $user)
    {
        $this->mapper = compact('gametype', 'team');
        $this->responder = $responder;
        $this->user = $user;
    }


    public function create(Request $request) : Response
    {
        return $this->handle();
    }


    public function edit(Request $request) : Response
    {
        $ladder = $request->getAttributes()->get('ladder');

        return $this->handle([
            'games' => $this->user->getAdminPosition()->getGames(),
            'ladder' => $ladder,
            'teams' => $this->mapper['team']->leaderboardByScores($ladder->getId(), 32, 1)
        ]);
    }


    private function handle(array $data = []) : Response
    {
        return $this->responder->handle(array_merge($data, [
            'games' => $this->user->getAdminPosition()->getGames(),
            'gametypes' => $this->mapper['gametype']->findByGameIds(...$this->user->getAdminPosition()->getGames())
        ]));
    }
}
