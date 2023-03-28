<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Generate Unique ID Using Input
 *
 */

namespace Contracts\UUID;

interface UniqueGenerator
{

    /**
     *  Generate Unique ID With Input
     *
     *  @param string $name Input
     *  @return string Unique ID
     *  @throws Exception If UUID Namespace Is Invalid
     */
    public function generateDNS(string $name) : string;
    public function generateOID(string $name) : string;
    public function generateURL(string $name) : string;
    public function generateX500(string $name) : string;


    /**
     *  @param string $uuid Unique ID
     *  @return bool True If Unique ID Is Valid/Was Generated Using This Class
     */
    public function isValid(string $uuid) : bool;
}
