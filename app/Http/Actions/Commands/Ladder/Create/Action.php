<?php

namespace App\Http\Actions\Commands\Ladder\Create;

use App\Commands\Ladder\Create\Command;
use App\Http\Actions\Commands\Ladder\AbstractAction;
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
        $ladder = $this->execute($this->command, $this->input($request))->getResult();

        return $this->responder->handle(($ladder && !$ladder->isEmpty()) ? $ladder->getId() : null);
    }
}
