<?php

namespace App\Http\Actions\Commands\User\Update\Admin;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\User\Update\Admin\Command;
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


    public function handle(Request $request) : Response
    {
        $this->execute($this->command, array_merge($request->getInput()->intersect(['adminPosition', 'users']), [
            'editor' => $this->user->getId()
        ]));

        return $this->responder->handle();
    }
}
