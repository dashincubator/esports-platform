<?php

namespace App\Http\Actions\Commands\Ladder\Team\Lock;

use App\Commands\Ladder\Team\Lock\Command;
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


    public function handle(Request $request, string $platform, string $game, string $ladder, string $team) : Response
    {
        $team = $request->getAttributes()->get('team');
        $locked = $this->execute($this->command, ['id' => $team->getId()])->getResult();

        return $this->responder->handle($locked, $platform, $game, $ladder, $team->getSlug());
    }
}
