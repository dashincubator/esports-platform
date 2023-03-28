<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Template Extension
 *
 */

namespace Contracts\View\Extensions;

use Contracts\View\View;

interface Template extends View
{

    /**
     *  Set Template Path + Data To Be Rendered At A Later Time
     *
     *  @param string $path Path To Template
     *  @param array $data Data Used For Render
     */
    public function __invoke(string $path, array $data = []) : void;
}
