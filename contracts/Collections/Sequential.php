<?php declare(strict_types=1);

namespace Contracts\Collections;

use Contracts\Support\Arrayable;
use Countable;
use IteratorAggregate;

interface Sequential extends Arrayable, Countable, IteratorAggregate
{

    /**
     *  Delete All Values In List
     */
    public function clear() : void;


    /**
     *  Returns Whether Or Not The Value Exists
     *
     *  @param mixed $value
     *  @return bool True If Exists, Otherwise False
     */
    public function has($value) : bool;


    /**
     *  Returns Values Found In Collection And '$values'
     *
     *  @param array $values
     *  @return array
     */
    public function intersect(array $values) : array;


    /**
     *  Merge $value With Collection
     *
     *  @param mixed $value
     */
    public function merge(array $value) : void;


    /**
     *  Push $value Onto Beginning Of Collection
     *
     *  @param mixed $value
     */
    public function prepend($value) : void;


    /**
     *  Delete And Return The Last Element Of The Collection
     */
    public function pop();


    /**
     *  Push $value Onto End Of Collection
     *
     *  @param mixed $value
     */
    public function push($value) : void;


    /**
     *  Replace Entire Collection With '$values'
     *
     *  @param array $values
     */
    public function replace(array $values) : void;
}
