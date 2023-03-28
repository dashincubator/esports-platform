<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  FIFO ( First In First Out ) Queue
 *
 */
 
namespace Contracts\Collections;

use Contracts\Support\Arrayable;
use Countable;

interface Queue extends Countable, Arrayable
{

    /**
     *  Add Value To Queue
     *
     *  @param mixed $value Value Being Added To Queue
     */
    public function add($value) : void;


    /**
     *  Delete All Values In Queue
     */
    public function clear() : void;


    /**
     *  Returns Total Count Of Values In Queue
     *
     *  @return int Total Count Of Values In Queue
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
     *  Returns Next Value In Queue, Also Removes From The Queue
     *
     *  @return mixed Value From Queue
     */
    public function next();


    /**
     *  Peeks At The Value At The Beginning Of The Queue
     *
     *  @return mixed Value At The Beginning Of The Queue, Otherwise Null
     */
    public function peek();
}
