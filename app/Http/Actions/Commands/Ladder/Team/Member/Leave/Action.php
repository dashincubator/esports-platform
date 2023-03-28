<?php

namespace App\Http\Actions\Commands\Ladder\Team\Member\Leave;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\Ladder\Team\Member\Leave\Command;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;

    private $user;


    public function __construct(Command $command, Responder $responder, User $user)
    {
        $this->command = $command;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request, string $platform, string $game, string $ladder, string $team) : Response
    {
        $this->execute($this->command, [
            'team' => $request->getAttributes()->get('team')->getId(),
            'user' => $this->user->getId()
        ]);

        return $this->responder->handle($platform, $game, $ladder, $team);
    }
}
