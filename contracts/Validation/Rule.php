<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Input Validation Rule
 *
 */

namespace Contracts\Validation;

interface Rule
{

    /**
     *  Whether Or Not Input Passes The Rule
     *
     *  @param mixed $args [0] => Form Input, [1+] => Rule List Arguments
     *  @return bool True If Input Passes Rule, Otherwise False
     */
    public function passes(...$args) : bool;
}
