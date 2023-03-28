<?php

namespace App\Http\Actions\Commands\User\AdminPosition\Create;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(?int $id) : Response
    {
        $route = 'admincp.admin.position.create';

        if ($id) {
            $route = 'admincp.admin.position.edit';
        }

        return $this->redirect->render($route, compact('id'));
    }
}
