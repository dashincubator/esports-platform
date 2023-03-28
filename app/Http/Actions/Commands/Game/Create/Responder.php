<?php

namespace App\Http\Actions\Commands\Game\Create;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(?int $id) : Response
    {
        $route = 'admincp.game.create';

        if ($id) {
            $route = 'admincp.game.edit';
        }

        return $this->redirect->render($route, compact('id'));
    }
}
