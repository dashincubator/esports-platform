<?php

namespace App\Http\Actions\Commands\User\Bank\Deposit\Square\Checkout;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(string $url) : Response
    {
        return $this->redirect->render($url);
    }
}
