<?php

namespace App\Http\Actions\Commands\Organization\Create;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(?int $id) : Response
    {
        $route = 'admincp.organization.create';

        if ($id) {
            $route = 'admincp.organization.edit';
        }

        return $this->redirect->render($route, compact('id'));
    }
}
