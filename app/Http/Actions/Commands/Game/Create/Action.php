<?php

namespace App\Http\Actions\Commands\Game\Create;

use App\Commands\Game\Create\Command;
use App\Http\Actions\Commands\Game\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;


    public function __construct(Command $command, Responder $responder)
    {
        $this->command = $command;
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        $game = $this->execute($this->command, $this->input($request->getInput()))->getResult();

        return $this->responder->handle(($game && !$game->isEmpty()) ? $game->getId() : 0);
    }
}
