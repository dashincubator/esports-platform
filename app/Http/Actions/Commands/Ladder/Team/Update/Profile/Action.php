<?php

namespace App\Http\Actions\Commands\Ladder\Team\Update\Profile;

use App\Http\Actions\AbstractAction;
use App\Commands\Ladder\Team\Update\Profile\Command;
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
        $updated = $this->execute($this->command, array_merge(
            $request->getFiles()->intersect(['avatar', 'banner']),
            $request->getInput()->intersect(['bio']),
            [
                'id' => $request->getAttributes()->get('team')->getId()
            ]
        ))->getResult();

        return $this->responder->handle($request->isAjax(), $platform, $game, $ladder, $team, $updated);
    }
}
