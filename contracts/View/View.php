<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Contains All Information Required To Render A View File Using The View Engine
 *
 */

namespace Contracts\View;

interface View
{

    /**
     *  @return array View Data
     */
    public function getData() : array;


    /**
     *  @return string File Directory Path
     */
    public function getDirectory() : string;


    /**
     *  @return string File Path
     */
    public function getPath() : string;
}
