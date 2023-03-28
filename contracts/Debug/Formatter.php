<?php declare(strict_types=1);

namespace Contracts\Debug;

use Throwable;

interface Formatter
{

    /**
     *  Format Array Or Throwable Messages ( ErrorException, Exception )
     *
     *  @param Throwable $e
     *  @return array Formatted Array
     */
    public function format(Throwable $e) : array;
}
