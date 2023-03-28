<?php

namespace App\Http\Actions\Commands\User\Update\Profile;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\{Request, Response};

class Responder extends AbstractResponder
{

    public function handle(Request $request, bool $updated) : Response
    {
        if ($request->isAjax()) {
            return $this->json->render([
                'messages' => $this->flash->toArray(),
                'success' => $updated
            ]);
        }

        return $this->redirect->render('account.edit');
    }
}
