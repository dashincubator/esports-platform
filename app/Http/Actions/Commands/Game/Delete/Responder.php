<?php

namespace App\Http\Actions\Commands\Game\Delete;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle() : Response
    {
        return $this->redirect->render('index');
    }
}
