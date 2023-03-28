<?php

namespace App\Http\Actions\Commands\User\AdminPosition\Delete;

use App\User;
use App\Commands\User\AdminPosition\Delete\Command;
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
        $this->execute($this->command, [
            'editor' => $this->user->getId(),
            'id' => $id
        ]);

        return $this->responder->handle();
    }
}
