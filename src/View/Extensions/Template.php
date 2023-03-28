<?php declare(strict_types=1);

namespace System\View\Extensions;

use Contracts\View\Extensions\Template as Contract;
use System\View\View;

class Template extends View implements Contract
{

    public function __invoke(string $path, array $data = []) : void
    {
        $this->setData($data);
        $this->setPath($path);
    }
}
