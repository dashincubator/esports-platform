<?php

namespace App\Http\Actions\Web\Index;

use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

use App\User;
use App\Services\Api\ModernWarfare\Auth;
use App\DataSource\User\Mapper;

use App\DataSource\Ladder\Mapper as LadderMapper;

class Action extends AbstractAction
{

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }


    public function handle(Request $request, int $id = 0, string $code = '') : Response
    {
        return $this->responder->handle(compact('code', 'id'));
    }
}
