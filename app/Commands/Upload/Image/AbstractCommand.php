<?php

namespace App\Commands\Upload\Image;

use App\Commands\AbstractCommand as AbstractParent;
use Contracts\Upload\{File, Image};

abstract class AbstractCommand extends AbstractParent
{

    private $image;


    public function __construct(AbstractFilter $filter, Image $image)
    {
        $this->filter = $filter;
        $this->image = $image;
    }


    protected function run(string $default, File $file, $name) : string
    {
        $path = $this->image->upload($file, $name);

        if (!$path) {
            $this->filter->writeUploadFailedMessage();

            return $default;
        }
        else {
            $this->filter->writeSuccessMessage();

            return $path;
        }
    }
}
