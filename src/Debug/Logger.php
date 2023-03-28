<?php declare(strict_types=1);

namespace System\Debug;

use Contracts\Debug\{Logger as Contract, Formatter};
use Contracts\IO\FileSystem;
use Throwable;

class Logger implements Contract
{

    private $filesystem;

    private $formatter;

    private $path;


    public function __construct(FileSystem $filesystem, Formatter $formatter, string $path)
    {
        $this->filesystem = $filesystem;
        $this->formatter = $formatter;
        $this->path = $path;

        if (!$filesystem->isDirectory($dir = dirname($path))) {
            $filesystem->makeDirectory($dir);
        }

        if (!$filesystem->exists($path)) {
            $filesystem->write($path, '');
        }
    }


    public function log(Throwable $e) : bool
    {
        return (bool) $this->filesystem->append($this->path, "\n" . json_encode($this->formatter->format($e)));
    }
}
