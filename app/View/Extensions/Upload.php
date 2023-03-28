<?php

namespace App\View\Extensions;

class Upload
{

    private $paths;


    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }


    public function path(string $path, string $filename) : string
    {
        if (!$filename) {
            $filename = '0.jpg';

            if ($path === 'user.avatar') {
                $filename = 'avatar.svg';
            }

            $path = 'root';
        }

        $path = "{$this->paths[$path]}/{$filename}";
        $path = explode('/public/', $path, 2)[1];

        return "/{$path}";
    }
}
