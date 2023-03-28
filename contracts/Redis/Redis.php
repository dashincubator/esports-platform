<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Redis Adapter/Wrapper
 *
 */

namespace Contracts\Redis;

use Closure;
use Contracts\Cache\Redis as Cache;

interface Redis extends Cache
{

    /**
     *  @param Closure $lookup If Value Is Missing Perform DB Lookup For Missing Data
     *  @see PhpRedis
     */


    public function hDel($bucket, ...$keys) : void;


    public function hGet($bucket, $key, Closure $lookup);


    public function hGetAll($bucket, Closure $lookup);


    public function hMGet($bucket, array $key, Closure $lookup);


    public function hMSet($bucket, array $values) : void;


    public function hSet($bucket, $key, $values) : void;


    public function lLen($key) : int;


    public function lPop($key);


    public function lPush($key, $value) : bool;


    public function mGet(array $keys, Closure $lookup);


    // Start Pipeline, Pass Through '$fn', Execute Pipeline And Return Values
    public function pipeline(Closure $fn);


    public function rPush($key, $value) : bool;


    public function sAdd($key, ...$values) : void;


    public function sMembers($key, Closure $lookup);


    public function sRem($key, ...$values) : void;


    public function zAdd($key, float $score, $value) : bool;


    public function zRangeByScore($key, float $start, float $end, array $options = []) : array;


    // Use When Leaderboard Is Ranked By Lower Score ( 1st = Closest To Zero )
    public function zRank($key, $value);


    public function zRem($key, ...$values);


    // Use When Leaderboard Is Ranked By Highest Score ( 1st = Furthest From Zero )
    public function zRevRank($key, $value);
}
