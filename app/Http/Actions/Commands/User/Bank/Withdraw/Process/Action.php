<?php

namespace App\Http\Actions\Commands\User\Bank\Withdraw\Process;

use App\Commands\User\Bank\Withdraw\Process\Command;
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
        $this->execute($this->command, $request->getInput()->intersect(['id', 'processorTransactionId']));

        return $this->responder->handle($request->getPreviousUrl());
    }
}
