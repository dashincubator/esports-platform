<?php

namespace App\Http\Actions\Web\Error\NotFound;

use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        return $this->responder->handle();
    }
}
