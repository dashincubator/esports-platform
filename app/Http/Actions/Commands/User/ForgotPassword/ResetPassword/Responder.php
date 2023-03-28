<?php

namespace App\Http\Actions\Commands\User\ForgotPassword\ResetPassword;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(bool $reset) : Response
    {
        return $this->redirect->render(($reset ? 'index' : 'account.auth.reset-password'));
    }
}
