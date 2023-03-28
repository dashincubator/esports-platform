<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Generate Random Unique Id's
 *
 */

namespace Contracts\UUID;

interface RandomGenerator
{

    /**
     *  Generate Random Unique ID
     *
     *  @return string
     */
    public function generate() : string;


    /**
     *  @param string $uuid Unique Random ID
     *  @return bool True If Unique ID Is Valid/Was Generated Using This Class
     */
    public function isValid(string $uuid) : bool;
}
