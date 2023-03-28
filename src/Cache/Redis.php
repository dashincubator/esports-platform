<?php declare(strict_types=1);

namespace System\Cache;

use Closure;
use Contracts\Cache\Redis as Contract;
use Contracts\Redis\Manager;
use Exception;

class Redis extends AbstractCache implements Contract
{

    private $manager;

    // Name Of The Client To Connect To
    private $name;


    public function __construct(Manager $manager, string $name = 'default')
    {
        $this->manager = $manager;
        $this->name = $name;
    }


    public function clear() : void
    {
        $this->getClient()->flushAll();
    }


    public function delete(...$keys) : void
    {
        $this->getClient()->del(...array_map([$this, 'prefix'], $keys));
    }


    public function get($key, Closure $lookup)
    {
        $result = $this->getClient()->get($this->prefix($key));

        if ($result === false) {
            return $lookup($key);
        }

        return $this->unserialize($result);
    }


    protected function getClient()
    {
        return $this->manager->getClient($this->name);
    }


    public function has($key) : bool
    {
        return (bool) $this->getClient()->exists($this->prefix($key));
    }


    // Redis Keys Must Be Type String
    protected function prefix($prefix) : string
    {
        return (string) parent::prefix($prefix);
    }


    public function set($key, $value, int $lifetime = -1) : void
    {
        $key = $this->prefix($key);
        $value = $this->serialize($value);

        if ($lifetime === -1) {
            $this->getClient()->set($key, $value);
        }
        else {
            $this->getClient()->setEx($key, $lifetime, $value);
        }
    }
}
