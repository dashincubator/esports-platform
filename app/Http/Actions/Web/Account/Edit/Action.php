<?php

namespace App\Http\Actions\Web\Account\Edit;

use App\User;
use App\Http\Actions\AbstractAction;
use App\DataSource\User\Rank\Mapper as RankMapper;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $user;


    public function __construct(RankMapper $rank, Responder $responder, User $user)
    {
        $this->mapper = compact('rank');
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request) : Response
    {
        return $this->responder->handle([
            'ranks' => $this->mapper['rank']->findByUser($this->user->getId())
        ]);
    }
}
