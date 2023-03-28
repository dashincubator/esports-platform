<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Dot Notation Collection
 *
 */

namespace Contracts\Collections;

use Contracts\Support\Arrayable;
use Countable;
use IteratorAggregate;

interface Associative extends Arrayable, Countable, IteratorAggregate
{

    /**
     *  Delete All Values In Collection
     */
    public function clear() : void;


    /**
     *  Delete Key From Collection
     *
     *  @param mixed $keys
     */
    public function delete(...$keys) : void;


    /**
     *  Return Value Associated With '$key'
     *
     *  @param mixed $key
     *  @param mixed $default
     *  @return mixed Value Of The '$key' If Found; $default Value If `$default !== null`
     *  @throws Exception When Variable Does Not Exist And `$default === null`
     */
    public function get($key, $default = null);


    /**
     *  Returns Whether Or Not The Key Exists
     *
     *  @param mixed $key
     *  @return bool True If Exists, Otherwise False
     */
    public function has($key) : bool;


    /**
     *  Returns Keys Found In Collection And '$keys'
     *
     *  @param array $keys
     *  @return array
     */
    public function intersect(array $keys) : array;


    /**
     *  Find Key In Collection And Merge $value With Existing Value(s),
     *  If Not Found $value Is Set Within The Collection.
     *
     *  @param mixed $key
     *  @param mixed $value
     */
    public function merge($key, $value) : void;


    /**
     *  Find Key In Collection And Prepend $value With Existing Value(s),
     *  If Not Found $value Is Set Within The Collection.
     *
     *  @param mixed $key
     *  @param mixed $value
     */
    public function prepend($key, $value) : void;


    /**
     *  Find Key In Collection And Merge $value With Existing Value(s),
     *  If Not Found $value Is Set Within The Collection.
     *
     *  @param mixed $key
     *  @param mixed $value
     */
    public function push($key, $value) : void;


    /**
     *  Replace Entire Collection With '$values'
     *
     *  @param array $values
     */
    public function replace(array $values) : void;


    /**
     *  Sets A Value
     *
     *  @param mixed $key
     *  @param mixed $value
     */
    public function set($key, $value) : void;
}
