<?php

namespace App\Http\Actions\Commands\Ladder\Team\Invite\Respond;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\Ladder\Team\Invite\Accept\Command as AcceptCommand;
use App\Commands\Ladder\Team\Invite\Decline\Command as DeclineCommand;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;

    private $user;


    public function __construct(AcceptCommand $accept, DeclineCommand $decline, Responder $responder, User $user)
    {
        $this->command = compact('accept', 'decline');
        $this->responder = $responder;
        $this->user = $user;
    }


    public function handle(Request $request, string $platform, string $game, string $ladder, string $team) : Response
    {
        $action = 'decline';

        if ($request->getInput()->has('accept')) {
            $action = 'accept';
        }

        $this->execute($this->command[$action], [
            'team' => $request->getAttributes()->get('team')->getId(),
            'user' => $this->user->getId()
        ]);

        return $this->responder->handle($platform, $game, $ladder, $team);
    }
}
