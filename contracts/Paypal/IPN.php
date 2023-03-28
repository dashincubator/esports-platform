<?php

namespace Contracts\Paypal;

interface IPN
{

    /**
     *  Verify Paypal IPN Message
     *
     *  @return bool True If IPN Message Is Valid, Otherwise False
     */
    public function isValid() : bool;


    /**
     *  Use Paypal Sandbox Url For IPN Validation
     */
    public function useSandbox() : void;
}
