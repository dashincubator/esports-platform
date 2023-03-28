<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Simple Cache
 *
 */

namespace Contracts\Cache;

use Closure;
use Contracts\Encryption\Encrypter;

interface Cache
{

    /**
     *  Clear All Data From Cache
     */
    public function clear() : void;


    /**
     *  Delete $key From Cache
     *
     *  @param mixed $keys Name Of Key(s) To Delete
     */
    public function delete(...$keys) : void;


    /**
     *  Get Value Of $key From Cache
     *
     *  @param mixed $key Name Of Key
     *  @param Closure $lookup If Value Is Missing Perform DB Lookup For Missing Data
     */
    public function get($key, Closure $lookup);


    /**
     *  True If Cache Has Value, Otherwise False
     *
     *  @param mixed $key Name Of Key
     *  @return bool True If Exists, Otherwise False
     */
    public function has($key) : bool;


    /**
     *  Store Key Value Pair In Cache
     *
     *  @param mixed $key Name Of Key
     *  @param mixed $value Value Being Stored
     *  @param int $lifetime TTL In Seconds ( -1 Sets Data Without Expiration )
     */
    public function set($key, $value, int $lifetime = -1) : void;


    /**
     *  Set Encrypter To Use
     *
     *  @param Encrypter $encrypter
     */
    public function setEncrypter(Encrypter $encrypter) : void;


    /**
     *  Add Prefix To Each Key Set In Cache
     *
     *  @param mixed $prefix
     */
    public function setPrefix($prefix) : void;


    /**
     *  Set Whether Or Not To Use Encryption
     *
     *  @param bool $use True If Storage Should Use Encryption, Otherwise False
     */
    public function useEncryption(bool $use) : void;
}
