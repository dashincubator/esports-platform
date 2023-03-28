<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Universal Application Dispatcher
 *
 */

namespace Contracts\Bootstrap;

interface Application
{

    /**
     *  Dispatch Application
     *
     *  @param array Application Boot Stages
     *  @return mixed ( Up To Final Stage )
     */
    public function dispatch(array $stages);
}
