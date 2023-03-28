<?php declare(strict_types=1);

namespace Contracts\Jobs;

use Closure;
use Contracts\Support\Arrayable;

interface Job extends Arrayable
{

    /**
     *  Class Name To Instantiate
     *
     *  @return string
     */
    public function getClassName() : string;


    /**
     *  Class Method To Call/Execute
     *
     *  @return string
     */
    public function getMethod() : string;


    /**
     *  Parameters To Pass To Class Method
     *
     *  @return array
     */
    public function getParameters() : array;


    /**
     *  Prevent Job From Overlapping If True
     *
     *  @return bool
     */
    public function useLock() : bool;
}
