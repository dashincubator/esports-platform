<?php

namespace App\Http\Actions\Commands\Ladder\Team\Delete;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\Ladder\Team\Delete\Command;
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
        $deleted = $this->execute($this->command, ['id' => $request->getAttributes()->get('team')->getId()])->getResult();

        return $this->responder->handle($deleted, $platform, $game, $ladder, $team);
    }
}
