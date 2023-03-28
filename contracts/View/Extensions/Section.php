<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Sections Extension
 *
 */

namespace Contracts\View\Extensions;

interface Section
{

    /**
     *  Returns Section Contents Stored Under $key
     *
     *  @param string $key
     *  @return string Section Contents If Exists, Otherwise Empty String
     */
    public function __invoke(string $key) : string;


    /**
     *  End Section
     *
     *  If The Section Was Started With '$this->prepend' Prepend Section Contents
     *  To Existing Section If Exists
     *
     *  If The Section Was Started With '$this->push' Push Section Contents
     *  To Existing Section If Exists
     *
     *  If The Section Was Started With '$this->start' Replace Existing Section
     *  Data If Exists
     */
    public function end() : void;


    /**
     *  Returns Whether Or Not Section Under Variable Name Already Exists
     *
     *  @param string $key Section Name To Look For
     *  @return bool True If Section Exists, Otherwise False
     */
    public function has(string $key) : bool;


    /**
     *  Starts A New Template Section, Once End Is Called The Contents Of This
     *  Section Will Be Prepended Onto The Existing Section ( If One Exists )
     *
     *  @param string $key Section Key To Store Contents Under
     */
    public function prepend(string $key) : void;


    /**
     *  Starts A New Template Section, Once End Is Called The Contents Of This
     *  Section Will Be Pushed Onto The Existing Section ( If One Exists )
     *
     *  @param string $key Section Key To Store Contents Under
     */
    public function push(string $key) : void;


    /**
     *  Starts A New Template Section
     *
     *  @param string $key Section Key To Store Contents Under
     */
    public function start(string $key) : void;
}
