<?php

namespace App\Http\Actions\Commands\User\Bank\Deposit\Paypal\IPN;

use Contracts\Http\Response;

class Responder
{

    private $response;


    public function __construct(Response $response)
    {
        $this->response = $response;
    }


    public function handle() : Response
    {
        return $this->response;
    }
}
