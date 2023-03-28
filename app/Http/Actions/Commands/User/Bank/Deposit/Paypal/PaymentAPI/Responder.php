<?php

namespace App\Http\Actions\Commands\User\Bank\Deposit\Paypal\PaymentAPI;

use App\Http\Actions\AbstractResponder;
use Contracts\Http\Response;

class Responder extends AbstractResponder
{

    private const URL = "https://www.paypal.com/cgi-bin/webscr?";


    public function handle(bool $valid, array $data = []) : Response
    {
        if (!$valid) {
            return $this->redirect->render('index');
        }

        foreach ($data as $key => $value) {
            $data[$key] = stripslashes($value);
        }

        return $this->redirect->render(self::URL . http_build_query($data));
    }
}
