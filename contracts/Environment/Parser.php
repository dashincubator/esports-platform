<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Parse '.env' Files
 *
 */

namespace Contracts\Environment;

interface Parser
{

    /**
     *  Convert '.env' To Array For Environment Class
     *
     *  @param string $parsing Path To '.env' File
     *  @return array
     */
    public function parse(string $parsing) : array;
}
