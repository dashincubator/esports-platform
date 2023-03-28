<?php

namespace App\Http\Actions\Web\Admincp\Organizations;

use App\DataSource\Organization\Mapper;
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    public function __construct(Mapper $mapper, Responder $responder)
    {
        $this->mapper = $mapper;
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        return $this->responder->handle([
            'organizations' => $this->mapper->findAll()
        ]);
    }
}
