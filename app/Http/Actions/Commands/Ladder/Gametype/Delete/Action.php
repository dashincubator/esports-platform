<?php

namespace App\Http\Actions\Commands\Ladder\Gametype\Delete;

use App\User;
use App\Commands\Ladder\Gametype\Delete\Command;
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;

    private $user;


    public function __construct(Command $command, Responder $responder, User $user)
    {
        $this->command = $command;
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request, int $id) : Response
    {
        $this->execute($this->command, compact('id'));

        return $this->responder->handle();
    }
}
