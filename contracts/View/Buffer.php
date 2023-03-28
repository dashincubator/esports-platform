<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Output Buffer
 *
 */

namespace Contracts\View;

interface Buffer
{

    /**
     *  Returns Current Buffer Contents And Deletes Current Output Buffer ( ob_get_clean() )
     *
     *  @return array Associative Array With Ob Contents And $key Provided To Start Method
     *  ['contents' => '', 'key' => '']
     *  @throws Exception If ob_get_level() Is Missing
     */
    public function end() : array;


    /**
     *  Turn On Output Bufferring, And Map $key Against Output Buffering Level
     *
     *  @param string $key Variable Name To Map Against Ob Level
     */
    public function start(string $key = '') : void;
}
