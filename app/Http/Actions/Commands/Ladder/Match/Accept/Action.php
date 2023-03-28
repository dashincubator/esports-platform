<?php

namespace App\Http\Actions\Commands\Ladder\Match\Accept;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\Ladder\Match\Accept\Command;
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
        $match = $this->execute($this->command, array_merge($request->getInput()->intersect(['id', 'roster', 'team']), [
            'user' => $this->user->getId()
        ]))->getResult();

        return $this->responder->handle($platform, $game, $ladder, (($match && $match->isActive()) ? $match->getId() : 0));
    }
}
