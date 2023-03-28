<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  View Directory Factory
 *
 */

namespace Contracts\View;

use Closure;
use Contracts\View\Extensions\{Data, Extensions, Extension, Render, Section, Template};

interface Factory
{

    /**
     *  Returns New Instance Of Data Wrapper
     *
     *  @see Data::class Comments
     */
    public function createData(array $data) : Data;


    /**
     *  Returns New Instance Of View Extension Proxy
     *
     *  @param string $classname
     *  @return Extension
     */
    public function createExtension(string $classname) : Extension;


    /**
     *  Returns New Instance Of Render Extension
     *
     *  @see Render::class Comments
     */
    public function createRender(array $data = []) : Render;


    /**
     *  Returns New Instance Of Section Extension
     *
     *  @see Section::class Comments
     */
    public function createSection() : Section;


    /**
     *  Returns New Instance Of Template Section
     *
     *  @see Template::class Comments
     */
    public function createTemplate(string $path = '', array $data = []) : Template;


    /**
     *  Returns New Instance Of View
     *
     *  @see View::class Comments
     */
    public function createView(string $path = '', array $data = []) : View;
}
