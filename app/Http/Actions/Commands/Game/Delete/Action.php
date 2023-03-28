<?php

namespace App\Http\Actions\Commands\Game\Delete;

use App\Commands\Game\Delete\Command;
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;


    public function __construct(Command $command, Responder $responder)
    {
        $this->command = $command;
        $this->responder = $responder;
    }


    public function handle(Request $request, int $id) : Response
    {
        $this->execute($this->command, compact('id'));

        return $this->responder->handle();
    }
}
