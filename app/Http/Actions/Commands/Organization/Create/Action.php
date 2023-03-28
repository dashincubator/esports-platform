<?php

namespace App\Http\Actions\Commands\Organization\Create;

use App\Commands\Organization\Create\Command;
use App\Http\Actions\Commands\Organization\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;

    private $organization;


    public function __construct(Command $command, Responder $responder)
    {
        $this->command = $command;
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        $organization = $this->execute($this->command, $this->input($request->getInput()))->getResult();

        return $this->responder->handle(($organization && !$organization->isEmpty()) ? $organization->getId() : 0);
    }
}
