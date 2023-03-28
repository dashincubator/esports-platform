<?php

namespace App\Http\Actions\Commands\Game\Platform\Create;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(?int $id) : Response
    {
        $route = 'admincp.platform.create';

        if ($id) {
            $route = 'admincp.platform.edit';
        }

        return $this->redirect->render($route, compact('id'));
    }
}
