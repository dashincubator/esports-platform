<?php

namespace App\Http\Actions\Commands\Ladder\Team\Invite\Create;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\Ladder\Team\Invite\FindBy\Command;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;


    public function __construct(Command $command, Responder $responder)
    {
        $this->command = $command;
        $this->responder = $responder;
    }


    public function handle(Request $request, string $platform, string $game, string $ladder, string $team) : Response
    {
        $this->execute($this->command, array_merge($request->getInput()->intersect(['column', 'value']), [
            'team' => $request->getAttributes()->get('team')->getId()
        ]));

        return $this->responder->handle($platform, $game, $ladder, $team);
    }
}
