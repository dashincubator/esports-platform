<?php declare(strict_types=1);

namespace System\IO;

use Contracts\IO\FileSystem as Contract;
use DateTime;
use Exception;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileSystem implements Contract
{

    public function append(string $path, $data) : int
    {
        return $this->write($path, $data, FILE_APPEND);
    }


    public function chmod(string $path, int $mode) : bool
    {
        return chmod($path, $mode);
    }


    public function copyDirectory(string $from, string $to, int $flags = FilesystemIterator::SKIP_DOTS) : bool
    {
        if (!$this->exists($from)) {
            return false;
        }

        if (!$this->isDirectory($to) && !$this->makeDirectory($to, 0777, true)) {
            return false;
        }

        $items = new FilesystemIterator($from, $flags);

        foreach ($items as $item) {
            $destination = $to . '/' . $item->getBasename();
            $from = $item->getRealPath();

            if ($item->isDir()) {
                if (!$this->copyDirectory($from, $destination, $flags)) {
                    return false;
                }
            }
            elseif ($item->isFile()) {
                if (!$this->copyFile($from, $destination)) {
                    return false;
                }
            }
        }

        return true;
    }


    public function copyFile(string $from, string $to) : bool
    {
        return copy($from, $to);
    }


    public function deleteDirectory(string $path, bool $deleteDirectoryStructure = true) : bool
    {
        if (!$this->isDirectory($path)) {
            return false;
        }

        $items = new FilesystemIterator($path);

        foreach ($items as $item) {
            if ($item->isDir()) {
                if (!$this->deleteDirectory($item->getRealPath())) {
                    return false;
                }
            }
            elseif ($item->isFile()) {
                if (!$this->deleteFile($item->getRealPath())) {
                    return false;
                }
            }
        }

        if ($deleteDirectoryStructure) {
            return rmdir($path);
        }

        return true;
    }


    public function deleteFile(string $path) : bool
    {
        return @unlink($path);
    }


    public function exists(string $path) : bool
    {
        return file_exists($path);
    }


    private function existsOrFail(string $path) : void
    {
        if (!$this->exists($path)) {
            throw new Exception("FileSystem File '{$path}' Not Found");
        }
    }


    public function getChmod(string $path) : int
    {
        return mb_substr(sprintf('%o', fileperms($path)), -4);
    }


    public function getDirectories(string $path, bool $isRecursive = false) : array
    {
        if (!$this->isDirectory($path)) {
            return [];
        }

        $directories = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD
        );

        if (!$isRecursive) {
            $iterator->setMaxDepth(0);
        }

        foreach ($iterator as $path => $item) {
            if ($item->isDir()) {
                $directories[] = $path;
            }
        }

        return $directories;
    }


    public function getDirectoryName(string $path) : string
    {
        $this->existsOrFail($path);

        return pathinfo($path, PATHINFO_DIRNAME);
    }


    public function getExtension(string $path) : string
    {
        $this->existsOrFail($path);

        return pathinfo($path, PATHINFO_EXTENSION);
    }


    public function getFileName(string $path) : string
    {
        $this->existsOrFail($path);

        return pathinfo($path, PATHINFO_FILENAME);
    }


    public function getFileSize(string $path) : int
    {
        $this->existsOrFail($path);

        $fileSize = filesize($path);

        if ($fileSize === false) {
            throw new Exception("FileSystem Failed To Get File Size Of '{$path}'");
        }

        return $fileSize;
    }


    public function getFiles(string $path, bool $isRecursive = false) : array
    {
        if (!$this->isDirectory($path)) {
            return [];
        }

        $files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD
        );

        if (!$isRecursive) {
            $iterator->setMaxDepth(0);
        }

        foreach ($iterator as $path => $item) {
            if ($item->isFile()) {
                $files[] = $path;
            }
        }

        return $files;
    }


    public function getLastModified(string $path) : int
    {
        $this->existsOrFail($path);

        return filemtime($path);
    }


    public function glob(string $pattern, int $flags = 0) : array
    {
        $files = glob($pattern, $flags);

        if ($files === false) {
            throw new Exception("FileSystem Glob Failed For Pattern '{$pattern}' With Flags '{$flags}'");
        }

        return $files;
    }


    public function include(string $path, array $data = [])
    {
        return FileSystemIncludeFile($path, $data);
    }


    public function isDirectory(string $path) : bool
    {
        return is_dir($path);
    }


    public function isFile(string $path) : bool
    {
        return is_file($path);
    }


    public function isReadable(string $path) : bool
    {
        return is_readable($path);
    }


    public function isWritable(string $path) : bool
    {
        return is_writable($path);
    }


    public function makeDirectory(string $path, int $mode = 0777, bool $isRecursive = false) : bool
    {
        $result = mkdir($path, $mode, $isRecursive);

        if ($result) {
            $this->chmod($path, $mode);
        }

        return $result;
    }


    public function move(string $from, string $to) : bool
    {
        return rename($from, $to);
    }


    public function read(string $path) : string
    {
        if (!$this->isFile($path)) {
            throw new Exception("FileSystem Failed To Read File At Path '{$path}'");
        }

        return file_get_contents($path);
    }


    public function require(string $path, array $data = [])
    {
        return FileSystemRequireFile($path, $data);
    }


    public function write(string $path, $data, int $flags = 0) : int
    {
        $bytesWritten = file_put_contents($path, $data, $flags);

        if ($bytesWritten === false) {
            throw new Exception("FileSystem Failed To Write Data To Path '{$path}'");
        }

        return $bytesWritten;
    }
}


/**
 *  Isolate Scope Inside Function
 *
 *  @param string $path File Path
 *  @param array $data Extracted For File Contents
 *  @return mixed
 */

function FileSystemIncludeFile(string $path, array $data = [])
{
    extract($data, EXTR_SKIP);

    return include $path;
}

function FileSystemRequireFile(string $path, array $data = [])
{
    extract($data, EXTR_SKIP);

    return require $path;
}
