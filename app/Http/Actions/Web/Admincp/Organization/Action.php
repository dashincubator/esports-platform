<?php

namespace App\Http\Actions\Web\Admincp\Organization;

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
        return $this->responder->handle();
    }


    public function edit(Request $request) : Response
    {
        return $this->responder->handle([
            'organization' => $request->getAttributes()->get('organization')
        ]);
    }
}
