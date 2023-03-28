<?php declare(strict_types=1);

namespace Contracts\Debug;

use Throwable;

interface Renderer
{

    /**
     *  Render/Output Array Or Throwable Messages ( ErrorException, Exception )
     *
     *  @param Throwable $e
     */
    public function render(Throwable $e) : void;
}
