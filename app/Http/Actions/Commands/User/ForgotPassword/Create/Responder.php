<?php

namespace App\Http\Actions\Commands\User\ForgotPassword\Create;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(bool $created) : Response
    {
        return $this->redirect->render($created ? 'index' : 'account.auth.forgot-password');
    }
}
