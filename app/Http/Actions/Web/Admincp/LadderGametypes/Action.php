<?php

namespace App\Http\Actions\Web\Admincp\LadderGametypes;

use App\User;
use App\DataSource\Ladder\Gametype\Mapper;
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    public function __construct(Mapper $mapper, Responder $responder, User $user)
    {
        $this->mapper = $mapper;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request) : Response
    {
        return $this->responder->handle([
            'gametypes' => $this->mapper->findByGameIds(...$this->user->getAdminPosition()->getGames())
        ]);
    }
}
