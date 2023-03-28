<?php

namespace App\Http\Actions\Commands\Ladder\Match\Create;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\Ladder\Match\Create\Command;
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


    public function handle(Request $request, string $platform, string $game, string $ladder) : Response
    {
        $input = $request->getInput();

        if ($input->has('gametype')) {
            $this->execute($this->command, array_merge(
                [
                    'modifiers' => [],
                    'roster' => []
                ],
                $input->get("settings.{$input->get('gametype')}", []),
                $input->intersect(['gametype', 'modifiers', 'roster', 'team']),
                [
                    'ladder' => $request->getAttributes()->get('ladder')->getId(),
                    'user' => $this->user->getId()
                ]
            ));
        }

        return $this->responder->handle($platform, $game, $ladder);
    }
}
