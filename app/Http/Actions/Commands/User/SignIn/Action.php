<?php

namespace App\Http\Actions\Commands\User\SignIn;

use App\Commands\User\SignIn\Command;
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
        return $this->responder->handle(
            $this->execute($this->command, $request->getInput()->intersect(['identifier', 'password']))->getResult(),
            $request->getPreviousUrl()
        );
    }
}
