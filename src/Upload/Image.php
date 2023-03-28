<?php declare(strict_types=1);

namespace System\Upload;

use Contracts\IO\FileSystem;
use Contracts\Upload\{File, Image as Contract};
use Closure;
use Exception;

class Image implements Contract
{

    private const DIRECTORY_MODE = 0755;

    private const EXIF_TYPE_WHITELIST = [IMAGETYPE_JPEG, IMAGETYPE_PNG];

    private const EXT_WHITELIST = ['jpg', 'jpeg', 'png'];

    private const FILE_MODE = 0644;

    private const TYPE_WHITELIST = ['image/jpeg', 'image/png'];


    private $bytelimit;

    private $directory;

    private $filesystem;

    private $quality;


    public function __construct(FileSystem $filesystem, string $directory, int $mb = 10, int $quality = 80)
    {
        $this->bytelimit = $mb * 1048576;
        $this->directory = rtrim($directory, '/');
        $this->filesystem = $filesystem;
        $this->quality = $quality;

        if (ini_get('file_uploads') === false) {
            throw new Exception("File Uploads Are Disabled In php.ini");
        }

        if ((trim(ini_get('upload_max_filesize'), 'M') * 1048576) < $this->bytelimit) {
            throw new Exception("'upload_max_filesize' < Max File Size Used By Image Uploader");
        }

        if (!$this->filesystem->isDirectory($this->directory) && !$this->filesystem->makeDirectory($this->directory, self::DIRECTORY_MODE, true)) {
            throw new Exception("Failed To Create Image Upload Directory '{$this->directory}'");
        }
    }


    private function isValid(File $file) : bool
    {
        return $file->hasErrors() === false
            && $file->getSize() <= $this->bytelimit
            && in_array($file->getExtension(), self::EXT_WHITELIST)
            && in_array($file->getType(), self::TYPE_WHITELIST)
            && in_array(exif_imagetype($file->getRealPath()), self::EXIF_TYPE_WHITELIST);
    }


    protected function process(File $file, string $name, Closure $upload) : ?string
    {
        $path = $this->directory . '/' . $name;
        $resource = $this->isValid($file) ? imagecreatefromstring($file->getContents()) : false;

        if ($resource) {
            $this->processing($path);

            $success = $upload($path, $this->quality, $resource);

            $this->processed($path, $resource, $success);

            // Cachebusting File Name Saved In DB
            if ($success) {
                return array_reverse(explode('/', $path))[0] . "?m=" . time();
            }
        }

        return null;
    }


    private function processed(string $path, $resource, bool $success) : void
    {
        imagedestroy($resource);

        if ($success) {
            $this->filesystem->chmod($path, self::FILE_MODE);
        }
    }


    private function processing(string $path) : void
    {
        $this->filesystem->deleteFile($path);
    }


    public function upload(File $file, string $name) : ?string
    {
        $upload = function(string $path, int $quality, $resource) {
            imageinterlace($resource, 1);

            return imagejpeg($resource, $path, $quality);
        };

        return $this->process($file, "{$name}.jpg", $upload);
    }
}
