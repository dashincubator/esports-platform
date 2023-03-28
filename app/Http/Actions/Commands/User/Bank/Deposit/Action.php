<?php

namespace App\Http\Actions\Commands\User\Bank\Deposit;

use App\Http\Actions\AbstractAction;
use App\Http\Actions\Commands\User\Bank\Deposit\Paypal\PaymentAPI\Action as PaypalAction;
use App\Http\Actions\Commands\User\Bank\Deposit\Square\Checkout\Action as SquareAction;
use Contracts\Http\{Request, Response};

class Action extends AbstractAction
{

    private $paypal;

    private $square;


    public function __construct(PaypalAction $paypal, SquareAction $square)
    {
        $this->paypal = $paypal;
        $this->square = $square;
    }


    public function handle(Request $request) : Response
    {
        $processor = $request->getPost()->get('processor');

        if ($processor === 'square') {
            return $this->square->handle($request);
        }

        return $this->paypal->handle($request);
    }
}
