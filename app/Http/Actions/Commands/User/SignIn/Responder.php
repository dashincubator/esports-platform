<?php

namespace App\Http\Actions\Commands\User\SignIn;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(bool $signedin, string $url) : Response
    {
        return $this->redirect->render($signedin ? $url : 'account.auth.sign-in');
    }
}
