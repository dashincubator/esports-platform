<?php declare(strict_types=1);

namespace Contracts\Debug;

use Throwable;

interface Logger
{

    /**
     *  Log Array Or Throwable Messages ( ErrorException, Exception )
     *
     *  @param Throwable $e
     *  @return bool True If Logged, Otherwise False
     */
    public function log(Throwable $e) : bool;
}
