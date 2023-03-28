<?php

namespace App\Http\Actions\Commands\Ladder\Update;

use App\Commands\Ladder\Update\Command;
use App\Http\Actions\Commands\Ladder\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;


    public function __construct(Command $command, Responder $responder)
    {
        $this->command = $command;
        $this->responder = $responder;
    }


    public function handle(Request $request, int $id) : Response
    {
        $this->execute($this->command, array_merge($this->input($request), compact('id')));

        return $this->responder->handle($id);
    }
}
