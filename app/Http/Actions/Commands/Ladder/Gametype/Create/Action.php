<?php

namespace App\Http\Actions\Commands\Ladder\Gametype\Create;

use App\Commands\Ladder\Gametype\Create\Command;
use App\Http\Actions\Commands\Ladder\Gametype\AbstractAction;
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
        $gametype = $this->execute($this->command, $this->input($request->getInput()))->getResult();

        return $this->responder->handle(($gametype && !$gametype->isEmpty()) ? $gametype->getId() : null);
    }
}
