<?php

namespace App\Http\Actions\Commands\User\SignUp;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(bool $created) : Response
    {
        $route = 'account.auth.sign-up';

        if ($created) {
            $this->flash->success("
                Welcome to GAMRS! Please add your game id's below before joining any events and save changes.
            ");
            $route = 'account.edit';
        }

        return $this->redirect->render($route);
    }
}
