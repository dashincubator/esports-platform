<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Render Extension
 *
 */

namespace Contracts\View\Extensions;

interface Render
{

    /**
     *  Render Template Path With Data
     *  Template Must Have Access To Existing Engine Extensions + Shared Data
     *
     *  @param string $path Path To Template
     *  @param array $data Data Used For Render
     *  @return string Rendered Template Contents
     */
    public function __invoke(string $path, array $data = []) : string;
}
