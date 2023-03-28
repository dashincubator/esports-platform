<?php

namespace App\Http\Actions;

use App\Commands\{AbstractCommand, Response};
use Contracts\Collections\Associative as Collection;

abstract class AbstractAction
{

    protected $responder;


    public function execute(AbstractCommand $command, array $data = []) : Response
    {
        $response = $command->execute($data);

        $this->responder->flash($response);

        return $response;
    }
}
