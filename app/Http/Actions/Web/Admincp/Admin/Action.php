<?php

namespace App\Http\Actions\Web\Admincp\Admin;

use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Admin\Position\Mapper as AdminPositionMapper;
use App\Http\Actions\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    public function __construct(AdminPositionMapper $position, Responder $responder, UserMapper $user)
    {
        $this->mapper = compact('position', 'user');
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        return $this->responder->handle([
            'admin' => $this->mapper['user']->findAllAdmin(),
            'positions' => $this->mapper['position']->findAll()
        ]);
    }
}
