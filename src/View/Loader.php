<?php declare(strict_types=1);

namespace System\View;

use Contracts\View\Loader as Contract;

class Loader implements Contract
{

    // Output Buffer
    private $buffer;


    public function __construct(Buffer $buffer)
    {
        $this->buffer = $buffer;
    }


    public function load(string $path, array $data = []) : string
    {
        $this->buffer->start();

        ViewLoaderRequireFile($path, $data);

        return $this->buffer->end()['content'];
    }
}


/**
 *  Isolate Scope Inside Function
 *
 *  @param string $path Template File Path
 *  @param array $data Extracted For Template File Contents
 *  @return void Buffer Handles Retrieval Of Contents
 */
function ViewLoaderRequireFile(string $path, array $components = []) : void
{
    extract($components, EXTR_REFS | EXTR_SKIP);

    require $path;
}
