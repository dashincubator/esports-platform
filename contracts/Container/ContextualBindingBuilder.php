<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  IoC Container Contextual Binding Builder
 *
 */

namespace Contracts\Container;

interface ContextualBindingBuilder
{

    /**
     *  Value To Give Class When Contextual Binding Matches
     *
     *  @param Object|Closure|string
     */
    public function give($implementation) : void;

    
    /**
     *  Binding To Match->Replace When Resolving Class
     *
     *  @param string $abstract Parameter To Match
     *  @param self
     */
    public function needs(string $abstract) : ContextualBindingBuilder;
}
