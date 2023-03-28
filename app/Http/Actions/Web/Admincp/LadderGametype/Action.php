<?php

namespace App\Http\Actions\Web\Admincp\LadderGametype;

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


    public function create(Request $request) : Response
    {
        return $this->handle();
    }


    public function edit(Request $request) : Response
    {
        return $this->handle([
            'gametype' => $request->getAttributes()->get('gametype')
        ]);
    }


    private function handle(array $data = []) : Response
    {
        return $this->responder->handle(array_merge($data, [
            'games' => $this->user->getAdminPosition()->getGames()
        ]));
    }
}
