<?php

namespace App\Http\Actions\Commands\User\Update\Admin;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\{Request, Response};

class Responder extends AbstractResponder
{

    public function handle() : Response
    {
        return $this->redirect->render('admincp.admin.manage');
    }
}
