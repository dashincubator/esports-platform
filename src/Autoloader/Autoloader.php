<?php declare(strict_types=1);

namespace System\Autoloader;

class Autoloader
{

    private const FILE_EXTENSION = '.php';

    // Avoid Walking Through Namespaces Replacing Values
    private const PREFIX = ':';

    private const SEPARATOR_DIRECTORY = '/';

    private const SEPARATOR_NAMESPACE = '\\';


    // Cache Normalized Namespaces: ['Namespace' => 'Full Class Path']
    private $classes = [];

    // PSR4 Namespaces: ['Namespace' => 'Full Class Path']
    private $psr4 = [];


    public function add(string $psr4, string $path) : void
    {
        $psr4 = self::PREFIX . trim($psr4, self::SEPARATOR_NAMESPACE) . self::SEPARATOR_NAMESPACE;

        if (!array_key_exists($psr4, $this->psr4)) {
            $this->psr4[$psr4] = $this->normalize($path) . self::SEPARATOR_DIRECTORY;
        }
    }


    public function load(string $class) : bool
    {
        if (!array_key_exists($class, $this->classes)) {
            $this->classes[$class] = $this->normalize($class);
        }

        return AutoloaderRequireFile($this->classes[$class] . self::FILE_EXTENSION);
    }


    private function normalize(string $path) : string
    {
        $path = self::PREFIX . trim($path, self::SEPARATOR_NAMESPACE);
        $path = rtrim($path, self::SEPARATOR_DIRECTORY);

        return str_replace(
            array_merge(array_keys($this->psr4),   [self::PREFIX, self::SEPARATOR_NAMESPACE]),
            array_merge(array_values($this->psr4), ['',           self::SEPARATOR_DIRECTORY]),
            $path
        );
    }


    public function register() : void
    {
        // Taken From CodeIgnitor4 For Tiny Performance Boost
        // - http://php.net/manual/en/function.spl-autoload.php#78053
        spl_autoload_extensions('.php,.inc');

        spl_autoload_register([$this, 'load'], true, true);
    }


    public function unregister() : void
    {
        spl_autoload_unregister([$this, 'load']);
    }
}


/**
 *  Isolate Scope Inside Function ( Taken From Composer )
 *
 *  @param string $path Class File Path
 *  @return bool True If Required, Otherwise Exception
 */
function AutoloaderRequireFile(string $path) : bool
{
    return (bool) require $path;
}
