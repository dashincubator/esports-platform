<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  HTTP Cookie
 *
 */

namespace Contracts\Http;

interface ResponseCookie
{

    /**
     *  @return string
     */
    public function getDomain() : string;


    /**
     *  @return int
     */
    public function getExpiration() : int;


    /**
     *  @return string
     */
    public function getName() : string;


    /**
     *  @return string
     */
    public function getPath() : string;


    /**
     *  @return string
     */
    public function getSameSite() : string;


    /**
     *  @return string
     */
    public function getValue() : string;


    /**
     *  @return bool
     */
    public function isHttpOnly() : bool;


    /**
     *  @return bool
     */
    public function isSecure() : bool;


    /**
     *  @param string $domain
     */
    public function setDomain(string $domain) : void;


    /**
     *  @param int $expiration
     */
    public function setExpiration(int $expiration) : void;


    /**
     *  @param bool $isHttpOnly
     */
    public function setHttpOnly(bool $isHttpOnly) : void;


    /**
     *  @param string $name
     */
    public function setName(string $name) : void;


    /**
     *  @param string $path
     */
    public function setPath(string $path) : void;


    /**
     *  @param string $samesite
     */
    public function setSameSite(string $samesite) : void;


    /**
     *  @param bool $isSecure
     */
    public function setSecure(bool $isSecure) : void;


    /**
     *  @param mixed $value
     */
    public function setValue($value) : void;
}
