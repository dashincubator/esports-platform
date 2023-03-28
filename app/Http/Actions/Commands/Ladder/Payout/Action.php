<?php

namespace App\Http\Actions\Commands\Ladder\Payout;

use App\Commands\Ladder\Payout\Command;
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


    public function handle(Request $request, int $id) : Response
    {
        $data = $request->getInput()->intersect(['payout', 'withdrawable']);
        $data['withdrawable'] = $data['withdrawable'] ?? null;

        $response = $this->execute($this->command, array_merge($data, compact('id')));

        return $this->responder->handle($id, $response->getResult());
    }
}
