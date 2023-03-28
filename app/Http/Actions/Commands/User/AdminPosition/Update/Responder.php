<?php

namespace App\Http\Actions\Commands\User\AdminPosition\Update;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(int $id) : Response
    {
        return $this->redirect->render('admincp.admin.position.edit', compact('id'));
    }
}
