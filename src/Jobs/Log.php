<?php declare(strict_types=1);

namespace System\Jobs;

use Contracts\Jobs\Log as Contract;
use Contracts\Redis\Redis as Cache;

class Log implements Contract
{

    private const CACHE_KEY = 'Workers';


    private $cache;

    private $prefix;


    public function __construct(Cache $cache, string $prefix = 'Jobs:Log')
    {
        $this->cache = $cache;
        $this->prefix = $prefix;
    }


    public function activity(array $activity) : void
    {
        $this->cache->hSet($this->prefix(self::CACHE_KEY), (string) $activity['createdAt'], $activity);
    }


    public function delete(array $activity) : void
    {
        $this->cache->hDel($this->prefix(self::CACHE_KEY), (string) $activity['createdAt']);
    }


    private function prefix(string $key) : string
    {
        if ($this->prefix) {
            $key = "{$this->prefix}:{$key}";
        }

        return $key;
    }


    public function read() : array
    {
        return $this->cache->hGetAll($this->prefix(self::CACHE_KEY), function() {
            return [];
        });
    }
}
