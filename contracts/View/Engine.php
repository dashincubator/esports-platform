<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  View Rendering Engine
 *
 */

namespace Contracts\View;

use Closure;

interface Engine
{

    /**
     *  Register View Extension
     *
     *  @param string $key
     *  @param string $classname
     */
    public function addExtension(string $key, string $classname) : void;


    /**
     *  Register View Path Alias/Key
     *
     *  @param string $key
     *  @param string $path
     */
    public function addPath(string $key, string $path) : void;


    /**
     *  Render View And Return Contents
     *
     *  @param View $view
     *  @return string Rendered View Content
     */
    public function render(View $view) : string;
}
