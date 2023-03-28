<?php

namespace App\Http\Actions\Commands\User\SignOut;

use App\Commands\User\SignOut\Command;
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


    public function handle(Request $request) : Response
    {
        $this->execute($this->command);

        return $this->responder->handle($request->getPreviousUrl());
    }
}
