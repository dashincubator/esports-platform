<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Loads File Contents
 *
 */

namespace Contracts\View;

interface Loader
{

    /**
     *  Loads Contents Of View File
     *
     *  @param string $path File To Load
     *  @param array $data Data Is Extracted ( extract() ) For View File
     *  @return string Contents Of File
     */
    public function load(string $path, array $data = []) : string;
}
