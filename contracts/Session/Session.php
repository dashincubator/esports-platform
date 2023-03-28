<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Session Collection
 *
 */

namespace Contracts\Session;

use Contracts\Collections\Associative as Collection;

interface Session extends Collection
{

    /**
     *  Marks Newly Flashed Data As Old, And Deletes Old Flash Data
     */
    public function ageFlashKeys() : void;


    /**
     *  Flashes Data For Exactly One Request
     *
     *  @param string $key Name Of The Variable To Set
     *  @param mixed $value Value To Set
     */
    public function flash(string $key, $value) : void;


    /**
     *  Return Session Id
     *
     *  @return string
     */
    public function getId() : string;


    /**
     *  Reflash All Of The Flash Data
     */
    public function reflash() : void;


    /**
     *  Generate New Id + Flush Collection
     *
     *  @param boolean $flush If True Flush Collection
     */
    public function regenerate(bool $flush = true) : void;


    /**
     *  Set Id, Flush Collection
     *
     *  @param string $id Session Id
     */
    public function start(string $id) : void;
}
