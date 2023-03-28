<?php

namespace App\Http\Actions\Commands\Ladder\Team\Update\Roster;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\Ladder\Team\Member\Edit\Command;
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


    public function handle(Request $request, string $platform, string $game, string $ladder, string $team) : Response
    {
        $data = [];
        $input = $request->getInput();

        foreach (['kick', 'permissions'] as $key) {
            $data[$key] = $input->get($key, []);
        }

        $this->execute($this->command, array_merge(
            $data,
            [
                'editor' => $this->user->getId(),
                'team' => $request->getAttributes()->get('team')->getId()
            ]
        ));

        return $this->responder->handle($platform, $game, $ladder, $team);
    }
}
