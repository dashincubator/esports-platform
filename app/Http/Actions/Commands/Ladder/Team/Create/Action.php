<?php

namespace App\Http\Actions\Commands\Ladder\Team\Create;

use App\User;
use App\Commands\Ladder\Team\Create\Command;
use App\Http\Actions\AbstractAction;
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


    public function handle(Request $request, string $platform, string $game, string $ladder) : Response
    {
        $team = $this->execute($this->command, array_merge($request->getInput()->intersect(['name']),[
            'event' => $request->getAttributes()->get('ladder')->getId(),
            'user' => $this->user->getId()
        ]))->getResult();

        return $this->responder->handle($platform, $game, $ladder, (($team && !$team->isEmpty()) ? $team->getSlug() : ''));
    }
}
