<?php

namespace App\View\Extensions;

class Svg
{

    private $directory;


    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }


    public function __invoke(string $name) : void
    {
        if (!array_reverse(explode('/', $name))[0]) {
            return;
        }

        include $this->directory . "/{$name}.svg";
    }
}
