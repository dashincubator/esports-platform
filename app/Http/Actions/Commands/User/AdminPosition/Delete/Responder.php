<?php

namespace App\Http\Actions\Commands\User\AdminPosition\Delete;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle() : Response
    {
        return $this->redirect->render('admincp.admin.manage');
    }
}
