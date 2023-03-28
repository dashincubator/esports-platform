<?php declare(strict_types=1);

namespace System\Upload;

use Contracts\Upload\File;

class Png extends Image
{

    public function upload(File $file, string $name) : ?string
    {
        $upload = function(string $path, int $quality, $resource) {
            imagesavealpha($resource, true);

            return imagepng($resource, $path, (int) ((string) $quality)[0]);
        };

        return $this->process($file, "{$name}.png", $upload);
    }
}
