<?php

namespace App\Bootstrap\Providers\Contracts;

use App\Bootstrap\Providers\AbstractProvider;
use Contracts\Session\Session;

use System\Payment\Square\Checkout;

class SquareProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->bind(Checkout::class, null, [
            'L8VK412N0F0KG', // Location
            'EAAAEFQJrNILJ9BUDryaF1_0LRHiS0aD_ypsBHzAzcblZ-FcuDAyTqOGcNfAVAfc' // Token
        ]);
    }
}
