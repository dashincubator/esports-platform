<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  LIFO ( Last In First Out ) Stack
 *
 */

namespace Contracts\Collections;

use Contracts\Support\Arrayable;
use Countable;

interface Stack extends Countable, Arrayable
{

    /**
     *  Add Value To Stack
     *
     *  @param mixed $value Value Being Added To Stack
     */
    public function add($value) : void;


    /**
     *  Delete All Values In Stack
     */
    public function clear() : void;


    /**
     *  Returns Total Count Of Values In Stack
     *
     *  @return int Total Count Of Values In Stack
     */
    public function count() : int;


    /**
     *  Returns Whether Or Not The Value Exists
     *
     *  @param mixed $value Value To Search For
     *  @return bool True If Value Existed, Otherwise False
     */
    public function has($value) : bool;


    /**
     *  Returns Next Value In Stack, Also Removes From The Stack
     *
     *  @return mixed Value From Stack
     */
    public function next();


    /**
     *  Peeks At The Value At The Beginning Of The Stack
     *
     *  @return mixed Value At The Beginning Of The Stack, Otherwise Null
     */
    public function peek();
}
