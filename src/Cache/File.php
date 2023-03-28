<?php declare(strict_types=1);

namespace System\Cache;

use Closure;
use Contracts\Cache\File as Contract;
use Exception;

class File extends AbstractCache implements Contract
{

    private const FOREVER_KEY = 'forever';


    // Directory Path
    private $directory;


    public function __construct(string $directory)
    {
        $this->directory = rtrim($directory, '/');

        if (!file_exists($this->directory)) {
            mkdir($this->directory, 0777, true);
        }
    }


    public function clear() : void
    {
        foreach (glob($this->directory . '/*') as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }


    public function delete(...$keys) : void
    {
        foreach ($keys as $key) {
            @unlink($this->prefix($key));
        }
    }


    public function get($key, Closure $lookup)
    {
        $value = $this->getContents($key)['d'];

        if (is_null($value)) {
            return $lookup($key);
        }

        return $value;
    }


    private function getContents($key) : array
    {
        if (file_exists($this->prefix($key))) {
            $data = $this->unserialize(file_get_contents($this->prefix($key)));
        }
        else {
            $data = ['d' => null, 't' => 0];
        }

        if ($data['t'] !== self::FOREVER_KEY && time() > $data['t']) {
            $this->delete($key);

            return ['d' => null, 't' => 0];
        }

        return $data;
    }


    public function has($key) : bool
    {
        return $this->getContents($key)['t'] !== 0;
    }


    protected function prefix($key) : string
    {
        return $this->directory . '/' . md5(parent::prefix($key));
    }


    public function set($key, $value, int $lifetime = -1) : void
    {
        if ($this->has($key)) {
            $this->delete($key);
        }

        file_put_contents($this->prefix($key), $this->serialize([
            'd' => $value,
            't' => ($lifetime === -1) ? self::FOREVER_KEY : time() + $lifetime
        ]));
    }
}
