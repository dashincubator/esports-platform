<?php

namespace App\Http\Actions\Web\Admincp\Platform;

use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }


    public function create(Request $request) : Response
    {
        return $this->handle();
    }


    public function edit(Request $request) : Response
    {
        return $this->handle([
            'platform' => $request->getAttributes()->get('platform')
        ]);
    }


    private function handle(array $data = []) : Response
    {
        return $this->responder->handle($data);
    }
}
