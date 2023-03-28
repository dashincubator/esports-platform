<?php

namespace App\Http\Actions\Commands\User\AdminPosition\Create;

use App\Organization;
use App\Commands\User\AdminPosition\Create\Command;
use App\Http\Actions\Commands\User\AdminPosition\AbstractAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $command;

    private $organization;


    public function __construct(Command $command, Organization $organization, Responder $responder)
    {
        $this->command = $command;
        $this->organization = $organization;
        $this->responder = $responder;
    }


    public function handle(Request $request) : Response
    {
        $position = $this->execute($this->command, array_merge($this->input($request->getInput()), [
            'organization' => $this->organization->getId()
        ]))->getResult();

        return $this->responder->handle(($position && !$position->isEmpty()) ? $position->getId() : 0);
    }
}
