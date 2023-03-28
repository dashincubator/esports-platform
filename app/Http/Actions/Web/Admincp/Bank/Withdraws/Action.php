<?php

namespace App\Http\Actions\Web\Admincp\Bank\Withdraws;

use App\Organization;
use App\Http\Actions\AbstractAction;
use App\DataSource\User\Bank\Withdraw\Mapper;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    public function __construct(Mapper $mapper, Organization $organization, Responder $responder)
    {
        $this->mapper = $mapper;
        $this->organization = $organization;
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        return $this->responder->handle([
            'stats' => $this->mapper->findStatsForAdminPanel($this->organization->getId()),
            'withdraw' => $this->mapper->findNextToProcess($this->organization->getId())
        ]);
    }
}
