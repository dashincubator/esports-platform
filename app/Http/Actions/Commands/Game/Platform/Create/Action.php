<?php

namespace App\Http\Actions\Commands\Game\Platform\Create;

use App\Commands\Game\Platform\Create\Command;
use App\Http\Actions\Commands\Game\Platform\AbstractAction;
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
        $platform = $this->execute($this->command, $this->input($request->getInput()))->getResult();

        return $this->responder->handle(($platform && !$platform->isEmpty()) ? $platform->getId() : null);
    }
}
