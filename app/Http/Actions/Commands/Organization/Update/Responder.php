<?php

namespace App\Http\Actions\Commands\Organization\Update;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(int $id) : Response
    {
        return $this->redirect->render('admincp.organization.edit', compact('id'));
    }
}
