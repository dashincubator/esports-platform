<?php

namespace App\Http\Actions\Web\Profile;

use App\DataSource\User\Rank\Mapper as RankMapper;
use App\DataSource\Ladder\Team\Member\Mapper as LadderTeamMemberMapper;
use App\DataSource\Ladder\Team\Mapper as LadderTeamMapper;
use App\DataSource\User\Account\Mapper as AccountMapper;
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $mapper;


    public function __construct(
        AccountMapper $account,
        LadderTeamMapper $ladderTeam,
        LadderTeamMemberMapper $ladderTeamMember,
        RankMapper $rank,
        Responder $responder
    ) {
        $this->mapper = compact('account', 'ladderTeam', 'ladderTeamMember', 'rank');
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        $user = $request->getAttributes()->get('user');

        $accounts = $this->mapper['account']->findByUser($user->getId());
        $ranks = $this->mapper['rank']->findByUser($user->getId());
        $teams = [
            'ladder' => $this->mapper['ladderTeam']->findByIds(
                ...$this->mapper['ladderTeamMember']->findTeamsByUser($user->getId())->column('team')
            )
        ];

        return $this->responder->handle(compact('accounts', 'user', 'ranks', 'teams'));
    }
}
