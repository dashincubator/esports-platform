<?php declare(strict_types=1);

namespace System\Redis;

use Closure;
use Contracts\Redis\Redis as Contract;
use Redis as Client;
use System\Cache\Redis as Cache;

class Redis extends Cache implements Contract
{

    // Redis Specific Cache Provides Access To Pipeline
    public function get($key, Closure $lookup)
    {
        $result = $this->getClient()->get($this->prefix($key));

        // Catch Pipeline
        if (is_object($result)) {
            return $result;
        }

        if ($result === false) {
            return $lookup($key);
        }

        return $this->unserialize($result);
    }


    public function hDel($bucket, ...$keys) : void
    {
        $this->getClient()->hDel($this->prefix($bucket), ...array_map('strval', $keys));
    }


    public function hGet($bucket, $key, Closure $lookup)
    {
        $result = $this->getClient()->hGet($this->prefix($bucket), (string) $key);

        // Catch Pipeline
        if (is_object($result)) {
            return $result;
        }

        if ($result === false) {
            $this->hSet($bucket, $key, $result = $lookup($key));
        }
        else {
            $result = $this->unserialize($result);
        }

        return $result;
    }


    public function hGetAll($bucket, Closure $lookup)
    {
        $result = $this->getClient()->hGetAll($this->prefix($bucket));

        // Catch Pipeline
        if (is_object($result)) {
            return $result;
        }

        if ($result === []) {
            $this->hMSet($bucket, $result = $lookup($bucket));
        }
        else {
            foreach ($result as $key => $value) {
                $result[$key] = $this->unserialize($value);
            }
        }

        return array_values($result);
    }


    public function hMGet($bucket, array $keys, Closure $lookup)
    {
        $found   = [];
        $missing = [];
        $result  = $this->getClient()->hMGet($this->prefix($bucket), array_map('strval', $keys));

        // Catch Pipeline
        if (is_object($result)) {
            return $result;
        }
        elseif ($result === false) {
            $result = [];
        }

        foreach ($result as $id => $value) {
            if ($value === false) {
                $missing[] = $id;
            }
            else {
                $found[$id] = $this->unserialize($value);
            }
        }

        if (count($missing)) {
            $this->hMSet($bucket, $missing = $lookup($missing));
        }

        return array_values(array_merge($found, $missing));
    }


    public function hMSet($bucket, array $values) : void
    {
        // Typecast Int To ( For Redis )
        foreach ($values as $key => $value) {
            $values[(string) $key] = $this->serialize($value);
        }

        $set = $this->getClient()->hMSet($this->prefix($bucket), $values);

        // If Cache Wasn't Updated Properly Delete To Force Cache Miss/Refetch
        if ($set === false) {
            $this->delete($bucket);
        }
    }


    public function hSet($bucket, $key, $values) : void
    {
        $set = $this->getClient()->hSet($this->prefix($bucket), (string) $key, $this->serialize($values));

        // If Cache Wasn't Updated Properly Delete Key To Force Cache Miss/Refetch
        if ($set === false) {
            $this->delete($bucket);
        }
    }


    public function lLen($key) : int
    {
        return (int) $this->getClient()->lLen($this->prefix($key));
    }


    public function lPop($key)
    {
        return $this->unserialize($this->getClient()->lPop($this->prefix($key)));
    }


    public function lPush($key, $value) : bool
    {
        return (bool) $this->getClient()->lPush($this->prefix($key), $this->serialize($value));
    }


    public function lRange($key, int $start, int $end) : array
    {
        return $this->getClient()->lRange($this->prefix($key), $start, $end);
    }


    public function mGet(array $keys, Closure $lookup)
    {
        $found   = [];
        $missing = [];
        $result  = $this->getClient()->mGet(array_map([$this, 'prefix'], $keys));

        // Catch Pipeline
        if (is_object($result)) {
            return $result;
        }

        foreach ($result as $index => $value) {
            if ($value !== false) {
                $found[] = $this->unserialize($value);
            }
            else {
                $missing[] = $keys[$index];
            }
        }

        if (count($missing)) {
            $found = array_merge($found, $lookup($missing));
        }

        return $found;
    }


    public function pipeline(Closure $fn)
    {
        $this->getClient()->multi(Client::PIPELINE);
        $fn($this);
        return $this->getClient()->exec();
    }


    public function rPush($key, $value) : bool
    {
        return (bool) $this->getClient()->rPush($this->prefix($key), $this->serialize($value));
    }


    public function sAdd($key, ...$values) : void
    {
        $this->getClient()->sAdd($this->prefix($key), ...array_map([$this, 'serialize'], $values));
    }


    public function sMembers($key, Closure $lookup)
    {
        $result = $this->getClient()->sMembers($this->prefix($key));

        if ($result === []) {
            $result = $lookup($key);

            $this->sAdd($key, ...$result);
        }
        else {
            $result = array_map([$this, 'unserialize'], $result);
        }

        return $result;
    }


    public function sRem($key, ...$values) : void
    {
        $this->getClient()->sRem($this->prefix($key), ...array_map([$this, 'serialize'], $values));
    }


    public function zAdd($key, float $score, $value) : bool
    {
        return (bool) $this->getClient()->zAdd($this->prefix($key), $score, $this->serialize($value));
    }


    public function zRangeByScore($key, float $start, float $end, array $options = []) : array
    {
        return $this->getClient()->zRangeByScore($this->prefix($key), (string) $start, (string) $end, $options);
    }


    // Use When Leaderboard Is Ranked By Lower Score ( 1st = Closest To Zero )
    public function zRank($key, $value)
    {
        return $this->getClient()->zRank($this->prefix($key), $this->serialize($value));
    }


    public function zRem($key, ...$values)
    {
        return $this->getClient()->zRem($this->prefix($key), ...array_map([$this, 'serialize'], $values));
    }


    // Use When Leaderboard Is Ranked By Highest Score ( 1st = Furthest From Zero )
    public function zRevRank($key, $value)
    {
        return $this->getClient()->zRevRank($this->prefix($key), $this->serialize($value));
    }
}
