<?php

namespace App\Http\Actions\Commands\Ladder\Match\Report;

use App\User;
use App\Http\Actions\AbstractAction;
use App\Commands\Ladder\Match\Report\Update\Command;
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


    public function handle(Request $request, string $platform, string $game, string $ladder, int $match, int $report) : Response
    {
        $this->execute($this->command, array_merge($request->getInput()->intersect(['placement']), [
            'id' => $report,
            'user' => $this->user->getId()
        ]));

        return $this->responder->handle($platform, $game, $ladder, $match);
    }
}
