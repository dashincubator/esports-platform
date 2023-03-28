<?php

namespace App\Http\Actions\Commands\Ladder\Create;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(?int $id) : Response
    {
        $route = 'admincp.ladder.create';

        if ($id) {
            $route = 'admincp.ladder.edit';
        }

        return $this->redirect->render($route, compact('id'));
    }
}
