<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Http Request Headers
 *
 */

namespace Contracts\Http;

use Contracts\Support\Arrayable;
use IteratorAggregate;

interface RequestHeaders extends Arrayable, IteratorAggregate
{

    /**
     *  Headers Can Have Multiple Values; By Default Collections Replace Values
     *
     *  @see $this->set()
     *  @param bool $replace Determines If Key Should Be Added Or Replaced Entirely
     */
    public function add(string $name, $value, bool $replace = true) : void;


    /**
     *  Delete Value From Header Collection
     *
     *  @param string $name
     */
    public function delete(string $name) : void;


    /**
     *  Return Value Associated With '$name'
     *
     *  @param string $name
     *  @param mixed $default
     *  @param bool $onlyReturnFirst True If We Only Want The First Header
     *  @return mixed Value Of The '$name' If Found, Otherwise '$default' Value
     */
    public function get(string $name, $default = null, bool $onlyReturnFirst = true);


    /**
     *  Returns Whether Or Not The Key Exists
     *
     *  @param string $name
     *  @return bool True If Exists, Otherwise False
     */
    public function has(string $name) : bool;


    /**
     *  Sets A Header Value
     *
     *  @param string $name
     *  @param mixed $value
     *  @param bool $replace Whether Or Not To Replace Value Or Merge
     */
    public function set(string $name, $value, bool $replace = true) : void;
}
