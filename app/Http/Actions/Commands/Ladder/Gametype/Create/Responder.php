<?php

namespace App\Http\Actions\Commands\Ladder\Gametype\Create;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(int $id = 0) : Response
    {
        $uri = 'admincp.ladder.gametype.create';

        if ($id > 0) {
            $uri = 'admincp.ladder.gametype.edit';
        }

        return $this->redirect->render($uri, compact('id'));
    }
}
