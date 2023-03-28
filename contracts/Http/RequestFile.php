<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Uploaded File
 *
 */

namespace Contracts\Http;

use Contracts\Upload\File;

interface RequestFile extends File
{

    /**
     *  @return string File Contents
     */
    public function getContents() : string;


    /**
     *  @return int Error Code
     */
    public function getError() : int;


    /**
     *  @return string Extension Provided By 'pathinfo'
     */
    public function getExtension() : string;


    /**
     *  @return string
     */
    public function getName() : string;


    /**
     *  @return int Size In bytes
     */
    public function getSize() : int;


    /**
     *  @return string MIME Type
     */
    public function getType() : string;


    /**
     *  @return bool True If File Has Errors, Otherwise False
     */
    public function hasErrors() : bool;
}
