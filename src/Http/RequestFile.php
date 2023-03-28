<?php declare(strict_types=1);

namespace System\Http;

use Contracts\Http\RequestFile as Contract;
use finfo;
use SplFileInfo;

class RequestFile extends SplFileInfo implements Contract
{

    // Error Message
    private $error = UPLOAD_ERR_OK;

    // Temporary Name Of The File
    private $name;

    // Size Of The File In Bytes
    private $size;

    // Mime Type Of The File
    private $type;


    public function __construct(
        string $path,
        string $name,
        int $size,
        string $type = '',
        int $error = UPLOAD_ERR_OK
    ) {
        parent::__construct($path);

        $this->error = $error;
        $this->name = $name;
        $this->size = $size;
        $this->type = $type;
    }


    public function getContents() : string
    {
        return file_get_contents($this->getRealPath());
    }


    public function getError() : int
    {
        return $this->error;
    }


    public function getExtension() : string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }


    public function getName() : string
    {
        return $this->name;
    }


    public function getSize() : int
    {
        return $this->size;
    }


    public function getType() : string
    {
        return (new finfo(FILEINFO_MIME_TYPE))->file($this->getPathname());
    }


    public function hasErrors() : bool
    {
        return $this->error !== UPLOAD_ERR_OK;
    }
}
