<?php

namespace App\Http\Actions\Commands\Game\Platform\Update;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(int $id) : Response
    {
        return $this->redirect->render('admincp.platform.edit', compact('id'));
    }
}
