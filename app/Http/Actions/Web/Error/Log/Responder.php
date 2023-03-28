<?php

namespace App\Http\Actions\Web\Error\Log;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    public function handle(array $data = []) : Response
    {
        return $this->html->render('@pages/error/500/html/development', $data);
    }
}
