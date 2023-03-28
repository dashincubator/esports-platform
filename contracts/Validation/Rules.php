<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Input Validation Rule Collection
 *
 */

namespace Contracts\Validation;

interface Rules
{

    /**
     *  Get A Rule By Alias/Name
     *
     *  @param string $name Name Of Rule
     *  @return null|Rule Rule If Found, Otherwise Null
     */
    public function get(string $name) : ?Rule;


    /**
     *  Set A Rule
     *
     *  @param string $name Alias/Name Of Rule
     *  @param Rule $rule
     */
    public function set(string $name, Rule $rule) : void;
}
