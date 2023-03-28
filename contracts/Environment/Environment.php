<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Get/Set Environment Variables
 *
 */

namespace Contracts\Environment;

interface Environment
{

    /**
     *  Return Value Of Environment Variable
     *
     *  @param string $name Name Of The Environment Variable To Get
     *  @param mixed $default Default Value If Environment Variable Does Not Exist
     *  @return mixed Value Of The Environment Variable, Or Default Parameter Value
     *  @throws Exception When Variable Does Not Exist And Default Is 'null'
     */
    public function get(string $name, $default = null);


    /**
     *  Set Value Of Environment Variable If It Does Not Already Exist
     *
     *  @param string $name Name Of the Environment Variable To Set
     *  @param mixed $value Value Of The Environment Variable
     */
    public function set(string $name, $value) : void;
}
