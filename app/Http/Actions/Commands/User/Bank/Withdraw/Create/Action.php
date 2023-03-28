<?php

namespace App\Http\Actions\Commands\User\Bank\Withdraw\Create;

use App\User;
use App\Commands\User\Bank\Withdraw\Create\Command;
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


    public function handle(Request $request) : Response
    {
        $this->execute($this->command, array_merge($request->getInput()->intersect(['amount', 'email']), [
            'processor' => 'paypal',
            'user' => $this->user->getId()
        ]));

        return $this->responder->handle($request->getPreviousUrl());
    }
}
