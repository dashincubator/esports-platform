<?php

namespace App\Http\Actions\Web\Game\Leaderboard;

use App\DataSource\User\{Mapper as UserMapper, Rank\Mapper as RankMapper};
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private const PER_PAGE = 50;


    private $mapper;


    public function __construct(RankMapper $rank, Responder $responder, UserMapper $user)
    {
        $this->mapper = compact('rank', 'user');
        $this->responder = $responder;
    }


    public function handle(Request $request, string $platform, string $game, int $page = 1) : Response
    {
        $game = $request->getAttributes()->get('game');

        $limit = self::PER_PAGE;
        $total = $this->mapper['rank']->countLeaderboardByScores($game->getId());
        $pages = ceil($total / $limit);

        if ($page > $pages) {
            $page = 1;
        }

        $ranks = $this->mapper['rank']->leaderboardByScores($game->getId(), $limit, $page);
        $users = $this->mapper['user']->findByIds(...$ranks->column('user'))->combine('id');

        return $this->responder->handle(compact('game', 'limit', 'page', 'pages', 'ranks', 'total', 'users'));
    }
}
