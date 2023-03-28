<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Application Provider
 *
 */

namespace Contracts\Bootstrap;

interface Provider
{

    /**
     *  Boot Bindings, etc.
     */
    public function boot();


    /**
     *  Register Bindings, etc.
     */
    public function register();
}
