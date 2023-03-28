<?php declare(strict_types=1);

namespace System\Environment;

use Contracts\Environment\Parser as Contract;
use Contracts\IO\FileSystem;
use Exception;

class Parser implements Contract
{

    private $filesystem;


    public function __construct(FileSystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }


    public function parse(string $parsing) : array
    {
        foreach (['read', 'process', 'split'] as $method) {
            $parsing = $this->{$method}($parsing);
        }

        return $parsing;
    }


    private function process(string $content) : array
    {
        // Split Lines
        $lines = explode("\n", str_replace(["\r\n", "\n\r", "\r"], "\n", $content));

        // Filter Comments
        $lines = array_filter(array_filter($lines), function($value) {
            return !in_array((trim($value)[0] ?? ''), ['', '#']);
        });

        return $lines;
    }


    private function read(string $path) : string
    {
        return $this->filesystem->read($path);
    }


    private function split(array $lines) : array
    {
        $pairs = [];

        foreach ($lines as $line) {
            $pair = explode('=', $line, 2);
            $pair = array_map('trim', $pair);

            if (count($pair) !== 2) {
                throw new Exception("Invalid Value Found In '.env' File");
            }

            list($key, $value) = $pair;

            $first = $value[0] ?? '';
            $last  = $value[strlen($value) - 1] ?? '';

            foreach (['"', "'"] as $character) {
                if ($first !== $character || $last !== $character) {
                    continue;
                }

                $value = trim($value, $character);
            }

            $pairs[$key] = $this->typecast($value);
        }

        return $pairs;
    }


    private function typecast($value)
    {
        if (
            is_string($value)
            && !is_numeric($value)
            && is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))
        ) {
            return (bool) $value;
        }

        return $value;
    }
}
